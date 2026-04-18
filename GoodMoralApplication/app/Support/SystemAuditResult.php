<?php

namespace App\Support;

class SystemAuditResult
{
    protected array $sections = [];

    public function addItem(string $section, string $item, string $severity = 'info'): void
    {
        $this->sections[$section][] = [
            'item' => $item,
            'severity' => $severity,
        ];
    }

    public function getSection(string $section): array
    {
        return $this->sections[$section] ?? [];
    }

    public function getSections(): array
    {
        return $this->sections;
    }

    public function getSectionCount(string $section): int
    {
        return count($this->sections[$section] ?? []);
    }

    public function getTotalIssues(): int
    {
        $total = 0;
        foreach ($this->sections as $items) {
            $total += count($items);
        }
        return $total;
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->sections as $section => $items) {
            $result[$section] = array_map(fn ($i) => $i['item'], $items);
        }
        return $result;
    }

    public function toSummary(): array
    {
        $summary = [];
        foreach ($this->sections as $section => $items) {
            $summary[$section] = count($items);
        }
        return $summary;
    }
}
