<?php

namespace App\Services;

use App\Support\SystemAuditResult;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class SystemAuditService
{
    protected SystemAuditResult $result;
    protected string $viewsPath;
    protected string $appPath;
    protected string $routesPath;
    protected string $controllersPath;
    protected string $modelsPath;
    protected string $servicesPath;
    protected string $componentsClassPath;
    protected string $componentsViewPath;

    // Cached source contents
    protected string $phpSources = '';
    protected string $bladeSources = '';
    protected array $bladeFiles = [];
    protected array $registeredRoutes = [];
    protected array $registeredRouteNames = [];

    // Project-specific safe-list: views that are used indirectly
    protected array $safeViewPrefixes = [
        'emails.',
        'pdf.',
        'vendor.pagination.',
        'certificates.',
    ];

    protected array $safeViews = [
        'notification',
        'notificationViolation',
        'welcome',
        'dashboard',
    ];

    public function __construct()
    {
        $this->result = new SystemAuditResult();
        $this->viewsPath = resource_path('views');
        $this->appPath = app_path();
        $this->routesPath = base_path('routes');
        $this->controllersPath = app_path('Http/Controllers');
        $this->modelsPath = app_path('Models');
        $this->servicesPath = app_path('Services');
        $this->componentsClassPath = app_path('View/Components');
        $this->componentsViewPath = resource_path('views/components');
    }

    public function run(?string $scope = null): SystemAuditResult
    {
        $this->loadSources();
        $this->loadRoutes();

        match ($scope) {
            'views' => $this->runViewAudits(),
            'controllers' => $this->runControllerAudits(),
            'routes' => $this->auditRouteIntegrity(),
            default => $this->runAll(),
        };

        return $this->result;
    }

    protected function runAll(): void
    {
        $this->runViewAudits();
        $this->runControllerAudits();
        $this->auditRouteIntegrity();
        $this->auditModels();
        $this->auditLegacyModules();
        $this->auditRefactorOpportunities();
    }

    protected function runViewAudits(): void
    {
        $this->auditUnusedViews();
        $this->auditBrokenReferences();
        $this->auditUnusedComponents();
    }

    protected function runControllerAudits(): void
    {
        $this->auditUnusedControllers();
        $this->auditUnreachableMethods();
        $this->auditRouteIntegrity();
    }

    // ─── Source Loading ──────────────────────────────────────────────────────────

    protected function loadSources(): void
    {
        // Load all PHP sources (app + routes, excluding vendor)
        $this->phpSources = collect()
            ->merge(File::allFiles($this->appPath))
            ->merge(File::allFiles($this->routesPath))
            ->filter(fn ($f) => $f->getExtension() === 'php')
            ->map(fn ($f) => File::get($f->getPathname()))
            ->implode("\n");

        // Load all Blade sources
        $this->bladeSources = collect(File::allFiles($this->viewsPath))
            ->filter(fn ($f) => Str::endsWith($f->getFilename(), '.blade.php'))
            ->map(fn ($f) => File::get($f->getPathname()))
            ->implode("\n");

        // Build blade file inventory
        $this->bladeFiles = collect(File::allFiles($this->viewsPath))
            ->filter(fn ($f) => Str::endsWith($f->getFilename(), '.blade.php'))
            ->map(function ($file) {
                $relative = str_replace($this->viewsPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relative = str_replace(['/', '\\'], '.', $relative);
                $relative = preg_replace('/\.blade\.php$/', '', $relative);
                return ltrim($relative, '.');
            })
            ->sort()
            ->values()
            ->all();
    }

    protected function loadRoutes(): void
    {
        $this->registeredRoutes = [];
        $this->registeredRouteNames = [];

        foreach (Route::getRoutes() as $route) {
            $action = $route->getActionName();
            $name = $route->getName();

            $this->registeredRoutes[] = [
                'uri' => $route->uri(),
                'action' => $action,
                'name' => $name,
                'methods' => $route->methods(),
            ];

            if ($name) {
                $this->registeredRouteNames[] = $name;
            }
        }
    }

    // ─── A. Unused Blade Views ───────────────────────────────────────────────────

    protected function auditUnusedViews(): void
    {
        foreach ($this->bladeFiles as $view) {
            if ($this->isViewUsed($view)) {
                continue;
            }

            $severity = $this->isSafeView($view) ? 'manual-review' : 'warning';
            $label = $severity === 'manual-review'
                ? "[Manual Review] {$view}"
                : $view;

            $this->result->addItem('Unused Views', $label, $severity);
        }
    }

    protected function isViewUsed(string $view): bool
    {
        $dotView = $view;
        $slashView = str_replace('.', '/', $view);

        // Case-insensitive check: 'admin.Application' should match view('admin.application')
        $dotViewLower = strtolower($dotView);
        $slashViewLower = strtolower($slashView);
        $phpSourcesLower = strtolower($this->phpSources);
        $bladeSourcesLower = strtolower($this->bladeSources);

        // Direct view() calls (case-insensitive for Windows compatibility)
        if ($this->sourceContainsAny($phpSourcesLower, [
            "view('{$dotViewLower}'",
            "view(\"{$dotViewLower}\"",
            "view('{$slashViewLower}'",
            "view(\"{$slashViewLower}\"",
        ])) {
            return true;
        }

        // Route::view()
        if (str_contains($phpSourcesLower, 'route::view(') && $this->sourceContainsAny($phpSourcesLower, [
            "'{$dotViewLower}'",
            "\"{$dotViewLower}\"",
        ])) {
            return true;
        }

        // Blade @include / @includeIf / @includeWhen / @extends
        if ($this->sourceContainsAny($bladeSourcesLower, [
            "@include('{$dotViewLower}'",
            "@include(\"{$dotViewLower}\"",
            "@includeif('{$dotViewLower}'",
            "@includeif(\"{$dotViewLower}\"",
            "@extends('{$dotViewLower}'",
            "@extends(\"{$dotViewLower}\"",
        ])) {
            return true;
        }

        // @includeWhen needs a more precise check
        if (str_contains($bladeSourcesLower, "@includewhen(") && $this->sourceContainsAny($bladeSourcesLower, [
            "'{$dotViewLower}'",
            "\"{$dotViewLower}\"",
        ])) {
            return true;
        }

        // Blade component usage: components.foo.bar => <x-foo.bar> or <x-foo-bar>
        if (Str::startsWith($view, 'components.')) {
            $componentName = substr($view, strlen('components.'));
            $componentDash = str_replace('.', '-', $componentName);
            $componentDot = $componentName;

            if ($this->sourceContainsAny($this->bladeSources, [
                "<x-{$componentDot}",
                "<x-{$componentDash}",
            ])) {
                return true;
            }
        }

        // Layout views used via component classes
        if ($view === 'layouts.guest' || $view === 'components.dashboard-layout') {
            return true;
        }

        // Profile partials are included from profile views
        if (Str::startsWith($view, 'profile.partials.')) {
            return true;
        }

        // Safe views (emails, pdf, etc.)
        if ($this->isSafeView($view)) {
            return true;
        }

        // Auth views — used by Laravel framework routes
        if (Str::startsWith($view, 'auth.')) {
            return true;
        }

        // Profile views — dynamically rendered by role
        if (Str::startsWith($view, 'profile.') && !Str::startsWith($view, 'profile.partials.')) {
            return true;
        }

        return false;
    }

    protected function isSafeView(string $view): bool
    {
        foreach ($this->safeViewPrefixes as $prefix) {
            if (Str::startsWith($view, $prefix)) {
                return true;
            }
        }

        return in_array($view, $this->safeViews);
    }

    // ─── B. Broken Blade References ──────────────────────────────────────────────

    protected function auditBrokenReferences(): void
    {
        $this->checkBrokenIncludes();
        $this->checkBrokenViewCalls();
        $this->checkBrokenComponents();
        $this->checkBrokenRouteNames();
    }

    protected function checkBrokenIncludes(): void
    {
        $bladeFilePaths = collect(File::allFiles($this->viewsPath))
            ->filter(fn ($f) => Str::endsWith($f->getFilename(), '.blade.php'));

        foreach ($bladeFilePaths as $file) {
            $content = File::get($file->getPathname());
            $relativePath = str_replace(
                resource_path('views') . DIRECTORY_SEPARATOR,
                '',
                $file->getPathname()
            );

            // Match @include('view.name') and @includeIf('view.name')
            preg_match_all('/@include(?:If|When|Unless)?\s*\(\s*[\'"]([^\'"]+)[\'"]/m', $content, $matches);

            foreach ($matches[1] as $viewRef) {
                if (!$this->viewExists($viewRef)) {
                    $this->result->addItem(
                        'Broken References',
                        "Missing @include: '{$viewRef}' in {$relativePath}",
                        'error'
                    );
                }
            }
        }
    }

    protected function checkBrokenViewCalls(): void
    {
        $phpFiles = collect()
            ->merge(File::allFiles($this->controllersPath))
            ->merge(File::allFiles($this->routesPath))
            ->filter(fn ($f) => $f->getExtension() === 'php');

        $reported = [];

        foreach ($phpFiles as $file) {
            $content = File::get($file->getPathname());
            $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());

            // Match view('name') or view("name")
            preg_match_all('/\bview\s*\(\s*[\'"]([^\'"]+)[\'"]/m', $content, $matches);

            foreach (array_unique($matches[1]) as $viewRef) {
                $key = "{$viewRef}|{$relativePath}";
                if (isset($reported[$key])) {
                    continue;
                }

                if (!$this->viewExists($viewRef)) {
                    $this->result->addItem(
                        'Broken References',
                        "Missing view(): '{$viewRef}' in {$relativePath}",
                        'error'
                    );
                    $reported[$key] = true;
                }
            }
        }
    }

    protected function checkBrokenComponents(): void
    {
        $bladeFilePaths = collect(File::allFiles($this->viewsPath))
            ->filter(fn ($f) => Str::endsWith($f->getFilename(), '.blade.php'));

        $allComponentTags = [];

        foreach ($bladeFilePaths as $file) {
            $content = File::get($file->getPathname());
            $relativePath = str_replace(
                resource_path('views') . DIRECTORY_SEPARATOR,
                '',
                $file->getPathname()
            );

            // Match <x-component-name> tags (but not <x-slot>)
            preg_match_all('/<x-([a-zA-Z0-9\-\.]+)/', $content, $matches);

            foreach ($matches[1] as $tag) {
                if ($tag === 'slot') {
                    continue;
                }
                $allComponentTags[$tag][] = $relativePath;
            }
        }

        foreach ($allComponentTags as $tag => $files) {
            if (!$this->componentExists($tag)) {
                $fileList = implode(', ', array_unique($files));
                $this->result->addItem(
                    'Broken References',
                    "Missing component: <x-{$tag}> used in {$fileList}",
                    'error'
                );
            }
        }
    }

    protected function checkBrokenRouteNames(): void
    {
        $reported = []; // Deduplicate: track "routeName|file" combos

        // Scan Blade files
        $bladeFilePaths = collect(File::allFiles($this->viewsPath))
            ->filter(fn ($f) => Str::endsWith($f->getFilename(), '.blade.php'));

        foreach ($bladeFilePaths as $file) {
            $content = File::get($file->getPathname());
            $relativePath = str_replace(
                resource_path('views') . DIRECTORY_SEPARATOR,
                '',
                $file->getPathname()
            );

            // Match route('name') calls in Blade
            preg_match_all('/route\s*\(\s*[\'"]([^\'"]+)[\'"]/m', $content, $matches);

            foreach (array_unique($matches[1]) as $routeName) {
                if ($this->isLikelyFalsePositiveRouteName($routeName)) {
                    continue;
                }

                $key = "{$routeName}|{$relativePath}";
                if (isset($reported[$key])) {
                    continue;
                }

                if (!in_array($routeName, $this->registeredRouteNames)) {
                    $this->result->addItem(
                        'Broken References',
                        "Missing route name: '{$routeName}' used in {$relativePath}",
                        'error'
                    );
                    $reported[$key] = true;
                }
            }
        }

        // Scan PHP controllers
        $phpFiles = collect(File::allFiles($this->controllersPath))
            ->filter(fn ($f) => $f->getExtension() === 'php');

        foreach ($phpFiles as $file) {
            $content = File::get($file->getPathname());
            $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());

            preg_match_all('/route\s*\(\s*[\'"]([^\'"]+)[\'"]/m', $content, $matches);

            foreach (array_unique($matches[1]) as $routeName) {
                if ($this->isLikelyFalsePositiveRouteName($routeName)) {
                    continue;
                }

                $key = "{$routeName}|{$relativePath}";
                if (isset($reported[$key])) {
                    continue;
                }

                if (!in_array($routeName, $this->registeredRouteNames)) {
                    $this->result->addItem(
                        'Broken References',
                        "Missing route name: '{$routeName}' used in {$relativePath}",
                        'error'
                    );
                    $reported[$key] = true;
                }
            }
        }
    }

    protected function isLikelyFalsePositiveRouteName(string $name): bool
    {
        // Single-word names that are likely Blade variables, not route names
        $falsePositives = ['token', 'type', 'status', 'id', 'name', 'email', 'password'];
        return in_array(strtolower($name), $falsePositives);
    }

    // ─── C. Controller Reachability ──────────────────────────────────────────────

    protected array $ignoredControllers = [
        'Controller', // Base controller class
    ];

    protected function auditUnusedControllers(): void
    {
        $controllerFiles = $this->getControllerFiles();
        $routedControllers = $this->getRoutedControllers();

        foreach ($controllerFiles as $fqcn => $filePath) {
            $shortName = class_basename($fqcn);

            if (in_array($shortName, $this->ignoredControllers)) {
                continue;
            }

            $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $filePath);

            if (!in_array($fqcn, $routedControllers)) {
                $this->result->addItem(
                    'Unused Controllers',
                    "{$relativePath} ({$shortName}) — no routes registered",
                    'warning'
                );
            }
        }
    }

    protected function auditUnreachableMethods(): void
    {
        $controllerFiles = $this->getControllerFiles();
        $routedActions = $this->getRoutedActions();

        foreach ($controllerFiles as $fqcn => $filePath) {
            $shortName = class_basename($fqcn);

            if (in_array($shortName, $this->ignoredControllers)) {
                continue;
            }

            if (!class_exists($fqcn)) {
                continue;
            }

            try {
                $reflection = new ReflectionClass($fqcn);
            } catch (\Throwable) {
                continue;
            }

            $publicMethods = collect($reflection->getMethods(ReflectionMethod::IS_PUBLIC))
                ->filter(fn ($m) => $m->getDeclaringClass()->getName() === $fqcn)
                ->filter(fn ($m) => !Str::startsWith($m->getName(), '__'))
                ->map(fn ($m) => $m->getName())
                ->values()
                ->all();

            $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $filePath);
            $shortName = class_basename($fqcn);

            foreach ($publicMethods as $method) {
                $actionKey = ltrim($fqcn, '\\') . '@' . $method;

                if (!in_array($actionKey, $routedActions)) {
                    // Check if the method is called from other PHP code
                    $isCalledElsewhere = str_contains($this->phpSources, "->{$method}(")
                        || str_contains($this->phpSources, "::{$method}(");

                    if (!$isCalledElsewhere) {
                        $this->result->addItem(
                            'Unreachable Methods',
                            "{$shortName}@{$method} — no route or internal reference",
                            'warning'
                        );
                    }
                }
            }
        }
    }

    // ─── D. Route Integrity ──────────────────────────────────────────────────────

    protected function auditRouteIntegrity(): void
    {
        // Check for routes pointing to missing controllers/methods
        foreach ($this->registeredRoutes as $route) {
            $action = $route['action'];

            if ($action === 'Closure' || str_contains($action, 'SerializableClosure')) {
                continue;
            }

            if (!str_contains($action, '@')) {
                // Invokable controller
                $class = $action;
                if (!class_exists($class)) {
                    $this->result->addItem(
                        'Route Issues',
                        "Route '{$route['uri']}' points to missing controller: {$class}",
                        'error'
                    );
                }
                continue;
            }

            [$class, $method] = explode('@', $action, 2);

            if (!class_exists($class)) {
                $this->result->addItem(
                    'Route Issues',
                    "Route '{$route['uri']}' points to missing controller: {$class}",
                    'error'
                );
                continue;
            }

            if (!method_exists($class, $method)) {
                $this->result->addItem(
                    'Route Issues',
                    "Route '{$route['uri']}' points to missing method: {$class}@{$method}",
                    'error'
                );
            }
        }

        // Check for duplicate route names
        $nameCounts = array_count_values(array_filter($this->registeredRouteNames));
        foreach ($nameCounts as $name => $count) {
            if ($count > 1) {
                $this->result->addItem(
                    'Route Issues',
                    "Duplicate route name: '{$name}' registered {$count} times",
                    'warning'
                );
            }
        }
    }

    // ─── E. Unused Blade Components ──────────────────────────────────────────────

    protected function auditUnusedComponents(): void
    {
        // Check component Blade files
        if (File::isDirectory($this->componentsViewPath)) {
            $componentBladeFiles = collect(File::allFiles($this->componentsViewPath))
                ->filter(fn ($f) => Str::endsWith($f->getFilename(), '.blade.php'));

            foreach ($componentBladeFiles as $file) {
                $relative = str_replace($this->componentsViewPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relative = str_replace(['/', '\\'], '.', $relative);
                $relative = preg_replace('/\.blade\.php$/', '', $relative);

                // Convert to x-tag format: foo.bar => foo-bar or foo.bar
                $dashName = str_replace('.', '-', $relative);
                $dotName = $relative;

                // Check if this component tag is used in any Blade file
                $isUsed = str_contains($this->bladeSources, "<x-{$dashName}")
                    || str_contains($this->bladeSources, "<x-{$dotName}");

                // Also check if it has a backing class (layout components are used implicitly)
                $className = 'App\\View\\Components\\' . str_replace('.', '\\', Str::studly($relative));
                $isLayoutComponent = str_contains(strtolower($relative), 'layout');

                if (!$isUsed && !$isLayoutComponent) {
                    $this->result->addItem(
                        'Unused Components',
                        "resources/views/components/{$file->getRelativePathname()} — no <x-{$dashName}> usage found",
                        'warning'
                    );
                }
            }
        }

        // Check component classes without matching Blade usage
        if (File::isDirectory($this->componentsClassPath)) {
            $componentClasses = collect(File::allFiles($this->componentsClassPath))
                ->filter(fn ($f) => $f->getExtension() === 'php');

            foreach ($componentClasses as $file) {
                $className = str_replace('.php', '', $file->getFilename());
                $kebabName = Str::kebab($className);

                $isUsed = str_contains($this->bladeSources, "<x-{$kebabName}");

                // Check if it renders a view that exists
                $viewExists = in_array("components.{$kebabName}", $this->bladeFiles);

                $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());

                if (!$isUsed && !$viewExists) {
                    $this->result->addItem(
                        'Unused Components',
                        "{$relativePath} — orphan class, no Blade usage or view file",
                        'error'
                    );
                }
            }
        }
    }

    // ─── F. Possibly Unused Models ───────────────────────────────────────────────

    protected function auditModels(): void
    {
        if (!File::isDirectory($this->modelsPath)) {
            return;
        }

        $modelFiles = collect(File::allFiles($this->modelsPath))
            ->filter(fn ($f) => $f->getExtension() === 'php');

        foreach ($modelFiles as $file) {
            $className = str_replace('.php', '', $file->getFilename());
            $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());

            // Count references in PHP sources (exclude the model file itself)
            $modelContent = File::get($file->getPathname());
            $otherSources = str_replace($modelContent, '', $this->phpSources);

            $refCount = 0;

            // Check class name usage
            $refCount += substr_count($otherSources, $className . '::');
            $refCount += substr_count($otherSources, "use App\\Models\\{$className}");
            $refCount += substr_count($otherSources, "new {$className}");
            $refCount += substr_count($otherSources, "{$className}::class");

            // Check for relationship references (belongsTo, hasMany, etc.)
            $refCount += substr_count($otherSources, "'{$className}'");
            $refCount += substr_count($otherSources, "\"{$className}\"");

            if ($refCount === 0) {
                $this->result->addItem(
                    'Possibly Unused Models',
                    "{$relativePath} ({$className}) — zero direct references found",
                    'warning'
                );
            } elseif ($refCount <= 2) {
                $this->result->addItem(
                    'Possibly Unused Models',
                    "[Manual Review] {$relativePath} ({$className}) — only {$refCount} reference(s)",
                    'manual-review'
                );
            }
        }
    }

    // ─── G. Legacy Module Detection ──────────────────────────────────────────────

    protected function auditLegacyModules(): void
    {
        $controllerFiles = $this->getControllerFiles();
        $routedControllers = $this->getRoutedControllers();

        foreach ($controllerFiles as $fqcn => $filePath) {
            if (in_array($fqcn, $routedControllers)) {
                continue;
            }

            $shortName = class_basename($fqcn);

            if (in_array($shortName, $this->ignoredControllers)) {
                continue;
            }

            $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $filePath);

            // Try to detect related views
            $possibleViewPrefixes = $this->guessViewPrefixes($fqcn);
            $relatedViews = [];

            foreach ($this->bladeFiles as $view) {
                foreach ($possibleViewPrefixes as $prefix) {
                    if (Str::startsWith($view, $prefix)) {
                        $relatedViews[] = $view;
                    }
                }
            }

            if (!empty($relatedViews)) {
                $viewList = implode(', ', array_slice($relatedViews, 0, 5));
                $extra = count($relatedViews) > 5 ? ' (and ' . (count($relatedViews) - 5) . ' more)' : '';

                $this->result->addItem(
                    'Legacy Modules',
                    "Controller: {$relativePath} — no routes, with related views: {$viewList}{$extra}",
                    'warning'
                );
            }
        }
    }

    protected function guessViewPrefixes(string $fqcn): array
    {
        $prefixes = [];

        // Extract namespace segments after Controllers
        $parts = explode('\\', $fqcn);
        $controllerIndex = array_search('Controllers', $parts);

        if ($controllerIndex !== false) {
            $segments = array_slice($parts, $controllerIndex + 1);
            $className = array_pop($segments); // Remove the class name

            // Try to guess from controller name: AcademicYearController => academic-year
            $baseName = str_replace('Controller', '', $className);
            $kebab = Str::kebab($baseName);
            $snake = Str::snake($baseName);

            // Build prefix from directory + controller name (most specific)
            if (!empty($segments)) {
                $dirPrefix = strtolower(implode('.', $segments));

                // Only use broad directory prefix if it's NOT a generic namespace like 'shared'
                $genericNamespaces = ['shared', 'common', 'base'];
                if (!in_array($dirPrefix, $genericNamespaces)) {
                    $prefixes[] = "{$dirPrefix}.{$kebab}";
                    $prefixes[] = "{$dirPrefix}.{$snake}";
                }

                // Also try admin.academic-year pattern (remap 'shared' controllers to 'admin')
                $prefixes[] = "admin.{$kebab}";
                $prefixes[] = "admin.{$snake}";
            }

            $prefixes[] = $kebab . '.';
            $prefixes[] = $snake . '.';
        }

        return array_unique($prefixes);
    }

    // ─── H. View Refactor Opportunities ──────────────────────────────────────────

    protected function auditRefactorOpportunities(): void
    {
        $this->detectDuplicateStatusBadges();
        $this->detectDuplicateFlashMessages();
        $this->detectDuplicateModalJS();
        $this->detectDuplicateTableLayouts();
        $this->detectNearDuplicateViews();
    }

    protected function detectDuplicateStatusBadges(): void
    {
        $badgePatterns = [
            'bg-green' => 'green status badge',
            'bg-red' => 'red status badge',
            'bg-yellow' => 'yellow status badge',
            'badge' => 'badge element',
        ];

        $bladeFilePaths = collect(File::allFiles($this->viewsPath))
            ->filter(fn ($f) => Str::endsWith($f->getFilename(), '.blade.php'))
            ->reject(fn ($f) => Str::contains($f->getRelativePathname(), 'components'));

        $filesWithBadges = 0;

        foreach ($bladeFilePaths as $file) {
            $content = File::get($file->getPathname());
            // Look for inline status badge patterns (switch/match on status + colored background)
            if (preg_match_all('/(?:bg-(?:green|red|yellow|blue|orange|gray)|badge|status).*?(?:approved|pending|rejected|completed|declined)/is', $content, $m)) {
                if (count($m[0]) >= 2) {
                    $filesWithBadges++;
                }
            }
        }

        if ($filesWithBadges >= 3) {
            $this->result->addItem(
                'Refactor Opportunities',
                "Duplicate status badge logic detected in {$filesWithBadges} view files — extract to <x-status-badge> component",
                'info'
            );
        }
    }

    protected function detectDuplicateFlashMessages(): void
    {
        $bladeFilePaths = collect(File::allFiles($this->viewsPath))
            ->filter(fn ($f) => Str::endsWith($f->getFilename(), '.blade.php'))
            ->reject(fn ($f) => Str::contains($f->getRelativePathname(), 'components'));

        $filesWithInlineFlash = 0;

        foreach ($bladeFilePaths as $file) {
            $content = File::get($file->getPathname());
            // Detect inline flash message blocks (session('success') / session('error') with HTML)
            if (preg_match('/session\s*\(\s*[\'"](?:success|error|warning|message)[\'"]\s*\)/i', $content)) {
                // Only count if it's NOT using @include('shared.alerts.flash')
                if (!str_contains($content, "shared.alerts.flash")) {
                    $filesWithInlineFlash++;
                }
            }
        }

        if ($filesWithInlineFlash >= 3) {
            $this->result->addItem(
                'Refactor Opportunities',
                "{$filesWithInlineFlash} views have inline flash messages — standardize with @include('shared.alerts.flash')",
                'info'
            );
        }
    }

    protected function detectDuplicateModalJS(): void
    {
        $patterns = [
            'viewDetails' => 0,
            'closeModal' => 0,
            'openApproveModal' => 0,
            'openRejectModal' => 0,
        ];

        $bladeFilePaths = collect(File::allFiles($this->viewsPath))
            ->filter(fn ($f) => Str::endsWith($f->getFilename(), '.blade.php'))
            ->reject(fn ($f) => Str::contains($f->getRelativePathname(), 'components'));

        foreach ($bladeFilePaths as $file) {
            $content = File::get($file->getPathname());
            foreach ($patterns as $fn => &$count) {
                if (preg_match('/function\s+' . preg_quote($fn, '/') . '\s*\(/', $content)) {
                    $count++;
                }
            }
            unset($count);
        }

        $duplicated = array_filter($patterns, fn ($c) => $c >= 2);
        foreach ($duplicated as $fn => $count) {
            $this->result->addItem(
                'Refactor Opportunities',
                "function {$fn}() is duplicated in {$count} views — extract to shared JS module",
                'info'
            );
        }
    }

    protected function detectDuplicateTableLayouts(): void
    {
        $bladeFilePaths = collect(File::allFiles($this->viewsPath))
            ->filter(fn ($f) => Str::endsWith($f->getFilename(), '.blade.php'))
            ->reject(fn ($f) => Str::contains($f->getRelativePathname(), ['components', 'pdf', 'emails', 'vendor']));

        $filesWithTables = 0;
        $tableFiles = [];

        foreach ($bladeFilePaths as $file) {
            $content = File::get($file->getPathname());
            if (substr_count($content, '<table') >= 1 && substr_count($content, '<th') >= 3) {
                $filesWithTables++;
                $tableFiles[] = $file->getRelativePathname();
            }
        }

        if ($filesWithTables >= 5) {
            $this->result->addItem(
                'Refactor Opportunities',
                "{$filesWithTables} views contain full table layouts — consider extracting common table structures to partials",
                'info'
            );
        }
    }

    protected function detectNearDuplicateViews(): void
    {
        // Compare views that look structurally similar by role prefix
        $rolePairs = [
            ['dean.minor', 'prog_coor.minor'],
            ['dean.major', 'prog_coor.major'],
            ['dean.major', 'sec_osa.major'],
        ];

        foreach ($rolePairs as [$viewA, $viewB]) {
            $pathA = resource_path('views/' . str_replace('.', '/', $viewA) . '.blade.php');
            $pathB = resource_path('views/' . str_replace('.', '/', $viewB) . '.blade.php');

            if (!File::exists($pathA) || !File::exists($pathB)) {
                continue;
            }

            $contentA = File::get($pathA);
            $contentB = File::get($pathB);

            // Simple similarity check by comparing stripped versions
            $stripA = preg_replace('/\s+/', ' ', strip_tags($contentA));
            $stripB = preg_replace('/\s+/', ' ', strip_tags($contentB));

            $lenA = strlen($stripA);
            $lenB = strlen($stripB);

            if ($lenA === 0 || $lenB === 0) {
                continue;
            }

            // Use length ratio as a quick similarity proxy (avoid expensive similar_text on large files)
            $ratio = min($lenA, $lenB) / max($lenA, $lenB);

            if ($ratio > 0.7) {
                // Take a sample to verify structural similarity
                $sampleA = substr($stripA, 0, 500);
                $sampleB = substr($stripB, 0, 500);

                similar_text($sampleA, $sampleB, $percent);

                if ($percent > 60) {
                    $pct = round($percent);
                    $this->result->addItem(
                        'Refactor Opportunities',
                        "{$viewA} and {$viewB} appear structurally similar ({$pct}% sample match) — consider merging",
                        'info'
                    );
                }
            }
        }
    }

    // ─── Helper Methods ──────────────────────────────────────────────────────────

    protected function viewExists(string $viewName): bool
    {
        $dotView = strtolower(str_replace('/', '.', $viewName));
        foreach ($this->bladeFiles as $file) {
            if (strtolower($file) === $dotView) {
                return true;
            }
        }
        return false;
    }

    protected function componentExists(string $tag): bool
    {
        // <x-foo-bar> resolves to components/foo-bar.blade.php or components/foo/bar.blade.php
        $dashPath = "components.{$tag}";
        $dotPath = 'components.' . str_replace('-', '.', $tag);

        // Direct match
        if (in_array($dashPath, $this->bladeFiles) || in_array($dotPath, $this->bladeFiles)) {
            return true;
        }

        // Check subdirectory patterns: <x-shared.modals.confirm-action>
        $subDirPath = 'components.' . str_replace('-', '.', $tag);
        foreach ($this->bladeFiles as $view) {
            if (Str::startsWith($view, 'components.')) {
                $componentPart = substr($view, strlen('components.'));
                $componentDash = str_replace('.', '-', $componentPart);
                $componentDot = $componentPart;

                if ($tag === $componentDash || $tag === $componentDot) {
                    return true;
                }
            }
        }

        // Check for component class
        $className = Str::studly(str_replace(['.', '-'], [' ', ' '], $tag));
        $className = str_replace(' ', '', $className);

        if (class_exists("App\\View\\Components\\{$className}")) {
            return true;
        }

        return false;
    }

    protected function getControllerFiles(): array
    {
        if (!File::isDirectory($this->controllersPath)) {
            return [];
        }

        $files = [];

        foreach (File::allFiles($this->controllersPath) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $content = File::get($file->getPathname());

            // Extract FQCN from namespace + class
            if (preg_match('/namespace\s+([^;]+)/m', $content, $ns) &&
                preg_match('/class\s+(\w+)/m', $content, $cl)) {
                $fqcn = $ns[1] . '\\' . $cl[1];
                $files[$fqcn] = $file->getPathname();
            }
        }

        return $files;
    }

    protected function getRoutedControllers(): array
    {
        $controllers = [];

        foreach ($this->registeredRoutes as $route) {
            $action = $route['action'];

            if ($action === 'Closure' || str_contains($action, 'SerializableClosure')) {
                continue;
            }

            $class = str_contains($action, '@')
                ? explode('@', $action)[0]
                : $action;

            $controllers[] = ltrim($class, '\\');
        }

        return array_unique($controllers);
    }

    protected function getRoutedActions(): array
    {
        $actions = [];

        foreach ($this->registeredRoutes as $route) {
            $action = $route['action'];

            if ($action === 'Closure' || str_contains($action, 'SerializableClosure')) {
                continue;
            }

            if (str_contains($action, '@')) {
                $actions[] = ltrim($action, '\\');
            } else {
                // Invokable controller
                $actions[] = ltrim($action, '\\') . '@__invoke';
            }
        }

        return array_unique($actions);
    }

    protected function sourceContainsAny(string $source, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($source, $needle)) {
                return true;
            }
        }
        return false;
    }
}
