<?php

namespace App\Console\Commands;

use App\Services\SystemAuditService;
use Illuminate\Console\Command;

class AuditSystem extends Command
{
    protected $signature = 'audit:system
        {--json : Output results as JSON}
        {--views-only : Only audit views and components}
        {--controllers-only : Only audit controllers and routes}
        {--routes-only : Only audit route integrity}';

    protected $description = 'Run a comprehensive system health audit: dead code, broken references, and refactor opportunities';

    public function handle(): int
    {
        $scope = $this->resolveScope();

        $this->output->writeln('');
        $this->output->writeln('<fg=green;options=bold>SYSTEM AUDIT REPORT</>');
        $this->output->writeln('<fg=green>═══════════════════</>');
        $this->output->writeln('');

        $this->output->write('<fg=yellow>Scanning project...</>');

        $service = new SystemAuditService();
        $result = $service->run($scope);

        $this->output->writeln(' <fg=green>done.</>');
        $this->output->writeln('');

        if ($this->option('json')) {
            return $this->outputJson($result);
        }

        return $this->outputFormatted($result);
    }

    protected function resolveScope(): ?string
    {
        if ($this->option('views-only')) {
            return 'views';
        }
        if ($this->option('controllers-only')) {
            return 'controllers';
        }
        if ($this->option('routes-only')) {
            return 'routes';
        }
        return null;
    }

    protected function outputFormatted($result): int
    {
        $sectionHeaders = [
            'Unused Views' => '📄 Unused Views',
            'Broken References' => '🔗 Broken References',
            'Unused Controllers' => '🎮 Unused Controllers',
            'Unreachable Methods' => '⚠️  Unreachable Controller Methods',
            'Route Issues' => '🛣️  Route Issues',
            'Unused Components' => '🧩 Unused Components',
            'Possibly Unused Models' => '📦 Possibly Unused Models',
            'Legacy Modules' => '🏚️  Legacy Modules',
            'Refactor Opportunities' => '🔧 Refactor Opportunities',
        ];

        $hasOutput = false;

        foreach ($sectionHeaders as $key => $header) {
            $items = $result->getSection($key);

            if (empty($items)) {
                continue;
            }

            $hasOutput = true;

            $this->output->writeln("<fg=cyan;options=bold>[{$header}]</>");

            foreach ($items as $entry) {
                $icon = match ($entry['severity']) {
                    'error' => '<fg=red>✘</>',
                    'warning' => '<fg=yellow>●</>',
                    'manual-review' => '<fg=blue>?</>',
                    default => '<fg=white>-</>',
                };
                $this->output->writeln("  {$icon} {$entry['item']}");
            }

            $this->output->writeln('');
        }

        if (!$hasOutput) {
            $this->output->writeln('<fg=green;options=bold>All clear! No issues detected.</>');
            $this->output->writeln('');
            return self::SUCCESS;
        }

        // Summary
        $this->output->writeln('<fg=green;options=bold>Summary</>');
        $this->output->writeln('<fg=green>───────</>');

        $totalIssues = 0;

        foreach ($sectionHeaders as $key => $header) {
            $count = $result->getSectionCount($key);
            if ($count > 0) {
                $color = $this->getSeverityColor($key);
                $label = str_replace(['📄 ', '🔗 ', '🎮 ', '⚠️  ', '🛣️  ', '🧩 ', '📦 ', '🏚️  ', '🔧 '], '', $header);
                $this->output->writeln("  <fg={$color}>{$label}: {$count}</>");
                $totalIssues += $count;
            }
        }

        $this->output->writeln('');
        $this->output->writeln("  <fg=white;options=bold>Total issues: {$totalIssues}</>");
        $this->output->writeln('');

        return $totalIssues > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function outputJson($result): int
    {
        $data = [
            'sections' => $result->toArray(),
            'summary' => $result->toSummary(),
            'total' => $result->getTotalIssues(),
        ];

        $this->output->writeln(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $result->getTotalIssues() > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function getSeverityColor(string $section): string
    {
        return match ($section) {
            'Broken References', 'Route Issues' => 'red',
            'Unused Controllers', 'Unreachable Methods', 'Unused Views', 'Unused Components' => 'yellow',
            'Legacy Modules', 'Possibly Unused Models' => 'blue',
            'Refactor Opportunities' => 'white',
            default => 'white',
        };
    }
}
