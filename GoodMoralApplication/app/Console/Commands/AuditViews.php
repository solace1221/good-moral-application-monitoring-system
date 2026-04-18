<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AuditViews extends Command
{
    protected $signature = 'audit:views';
    protected $description = 'Audit Blade views and detect possibly unused files with better Laravel awareness';

    public function handle()
    {
        $viewsPath = resource_path('views');
        $appPath = app_path();
        $routesPath = base_path('routes');

        $bladeFiles = collect(File::allFiles($viewsPath))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.blade.php'))
            ->map(function ($file) use ($viewsPath) {
                $relative = str_replace($viewsPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relative = str_replace(['/', '\\'], '.', $relative);
                $relative = preg_replace('/\.blade\.php$/', '', $relative);
                return ltrim($relative, '.');
            })
            ->sort()
            ->values();

        $phpSources = collect()
            ->merge(File::allFiles($appPath))
            ->merge(File::allFiles($routesPath))
            ->filter(fn ($file) => $file->getExtension() === 'php')
            ->map(fn ($file) => File::get($file->getPathname()))
            ->implode("\n");

        $bladeSources = collect(File::allFiles($viewsPath))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.blade.php'))
            ->map(fn ($file) => File::get($file->getPathname()))
            ->implode("\n");

        $possiblyUnused = [];
        $used = [];

        foreach ($bladeFiles as $view) {
            $dotView = $view;
            $slashView = str_replace('.', '/', $view);

            $isUsed = false;
            $reasons = [];

            // PHP view() / return view()
            if (
                str_contains($phpSources, "view('{$dotView}')") ||
                str_contains($phpSources, "view(\"{$dotView}\")") ||
                str_contains($phpSources, "view('{$slashView}')") ||
                str_contains($phpSources, "view(\"{$slashView}\")")
            ) {
                $isUsed = true;
                $reasons[] = 'Referenced by view()';
            }

            // Route::view()
            if (
                str_contains($phpSources, "Route::view(") &&
                (
                    str_contains($phpSources, "'{$dotView}'") ||
                    str_contains($phpSources, "\"{$dotView}\"") ||
                    str_contains($phpSources, "'{$slashView}'") ||
                    str_contains($phpSources, "\"{$slashView}\"")
                )
            ) {
                $isUsed = true;
                $reasons[] = 'Referenced by Route::view()';
            }

            // Blade includes / extends / components
            if (
                str_contains($bladeSources, "@include('{$dotView}')") ||
                str_contains($bladeSources, "@include(\"{$dotView}\")") ||
                str_contains($bladeSources, "@includeIf('{$dotView}')") ||
                str_contains($bladeSources, "@includeIf(\"{$dotView}\")") ||
                str_contains($bladeSources, "@includeWhen(") && (
                    str_contains($bladeSources, "'{$dotView}'") ||
                    str_contains($bladeSources, "\"{$dotView}\"")
                ) ||
                str_contains($bladeSources, "@extends('{$dotView}')") ||
                str_contains($bladeSources, "@extends(\"{$dotView}\")")
            ) {
                $isUsed = true;
                $reasons[] = 'Referenced in Blade include/extends';
            }

            // Blade component usage: resources/views/components/foo/bar.blade.php => <x-foo.bar> or <x-foo-bar>
            if (str_starts_with($view, 'components.')) {
                $componentName = substr($view, strlen('components.'));
                $componentDot = $componentName;
                $componentDash = str_replace('.', '-', $componentName);

                if (
                    str_contains($bladeSources, "<x-{$componentDot}") ||
                    str_contains($bladeSources, "<x-{$componentDash}")
                ) {
                    $isUsed = true;
                    $reasons[] = 'Used as Blade component';
                }
            }

            // Common Laravel special-case folders that should not be auto-flagged
            if (
                str_starts_with($view, 'emails.') ||
                str_starts_with($view, 'pdf.') ||
                str_starts_with($view, 'vendor.pagination.') ||
                $view === 'notification' ||
                $view === 'notificationViolation'
            ) {
                $isUsed = true;
                $reasons[] = 'Special Laravel/template view';
            }

            // Auth/profile/dashboard/welcome are often route- or framework-driven
            if (preg_match('/^(auth|profile|dashboard|welcome|layouts)\b/', $view)) {
                $isUsed = true;
                $reasons[] = 'Framework/app scaffold view';
            }

            if ($isUsed) {
                $used[$view] = $reasons;
            } else {
                $possiblyUnused[] = $view;
            }
        }

        $this->info('Audit complete.');
        $this->newLine();

        $this->line('Total Blade files: ' . $bladeFiles->count());
        $this->line('Likely used: ' . count($used));
        $this->line('Possibly unused: ' . count($possiblyUnused));
        $this->newLine();

        if (empty($possiblyUnused)) {
            $this->info('No possibly unused views found.');
            return self::SUCCESS;
        }

        $this->warn('Possibly unused views:');
        foreach ($possiblyUnused as $view) {
            $this->line(" - {$view}");
        }

        $this->newLine();
        $this->comment('Note: These are only POSSIBLY unused. Review manually before deleting.');

        return self::SUCCESS;
    }
}