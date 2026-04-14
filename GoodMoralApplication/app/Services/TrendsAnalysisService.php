<?php

namespace App\Services;

use App\Models\HistoricalViolationTrend;
use App\Models\StudentViolation;
use Illuminate\Support\Facades\DB;

class TrendsAnalysisService
{
    /**
     * Departments included in trends analysis.
     */
    private const TREND_DEPARTMENTS = ['SITE', 'SBAHM', 'SNAHS', 'SASTE'];

    /**
     * Load historical data from the database, keyed by department code.
     *
     * @return array<string, array<string, array{minor_count: int, major_count: int, population: int}>>
     */
    private function getHistoricalData(): array
    {
        $rows = HistoricalViolationTrend::join('departments', 'departments.id', '=', 'historical_violation_trends.department_id')
            ->whereIn('departments.department_code', self::TREND_DEPARTMENTS)
            ->select(
                'departments.department_code',
                'historical_violation_trends.academic_year',
                'historical_violation_trends.minor_count',
                'historical_violation_trends.major_count',
                'historical_violation_trends.population'
            )
            ->get();

        $data = [];
        foreach ($rows as $row) {
            $data[$row->department_code][$row->academic_year] = [
                'minor_count' => (int) $row->minor_count,
                'major_count' => (int) $row->major_count,
                'population'  => (int) $row->population,
            ];
        }

        return $data;
    }

    /**
     * Get live violation counts for the current academic year from student_violations.
     *
     * @param string $offenseType 'major' or 'minor'
     * @return array<string, int> department_code => count
     */
    private function getCurrentYearViolations(string $offenseType): array
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');

        // Academic year starts in August.
        // Before August → the AY started the previous calendar year.
        $ayStartYear = $currentMonth >= 8 ? $currentYear : $currentYear - 1;
        $academicYearStart = "{$ayStartYear}-08-01";

        return StudentViolation::where('offense_type', $offenseType)
            ->where('created_at', '>=', $academicYearStart)
            ->selectRaw('department, COUNT(DISTINCT student_id) as violator_count')
            ->groupBy('department')
            ->pluck('violator_count', 'department')
            ->toArray();
    }

    /**
     * Determine the two most recent historical academic years from the database.
     *
     * @return array [previousAY, comparisonAY]  e.g. ['2023-2024', '2024-2025']
     */
    private function getHistoricalAcademicYears(): array
    {
        $years = HistoricalViolationTrend::select('academic_year')
            ->distinct()
            ->orderBy('academic_year')
            ->pluck('academic_year')
            ->toArray();

        // Need at least two historical years to compute variance.
        // Pad with empty strings if there are fewer.
        $count = count($years);
        $previousAY   = $years[$count - 2] ?? '';
        $comparisonAY = $years[$count - 1] ?? '';

        return [$previousAY, $comparisonAY];
    }

    /**
     * Get the current academic year label (e.g. "2025-2026").
     */
    private function getCurrentAcademicYearLabel(): string
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        $ayStartYear = $currentMonth >= 8 ? $currentYear : $currentYear - 1;

        return $ayStartYear . '-' . ($ayStartYear + 1);
    }

    /**
     * Get major offense trends analysis data.
     */
    public function getMajorOffenseTrendsData(bool $includeDummyData = false): array
    {
        $historical = $this->getHistoricalData();
        [$previousAY, $comparisonAY] = $this->getHistoricalAcademicYears();
        $currentAYLabel = $this->getCurrentAcademicYearLabel();
        $currentYearViolations = $this->getCurrentYearViolations('major');

        $trendsData = [];
        foreach (self::TREND_DEPARTMENTS as $dept) {
            $prevData = $historical[$dept][$previousAY] ?? null;
            $compData = $historical[$dept][$comparisonAY] ?? null;

            $totalPopulation    = $compData['population'] ?? ($prevData['population'] ?? 0);
            $previousViolators  = $prevData['major_count'] ?? 0;
            $comparisonViolators = $compData['major_count'] ?? 0;
            $currentViolators   = $currentYearViolations[$dept] ?? 0;

            $rawDifference = $comparisonViolators - $previousViolators;
            $variancePercentage = $previousViolators > 0
                ? round((($previousViolators - $comparisonViolators) / $previousViolators) * 100, 2)
                : ($comparisonViolators > 0 ? -100.00 : 0.00);

            $trendsData[$dept] = [
                'department'              => $dept,
                'total_population'        => $totalPopulation,
                'violators_2023_2024'     => $previousViolators,
                'violators_june_2025'     => $comparisonViolators,
                'current_violators'       => $currentViolators,
                'variance_june'           => $rawDifference,
                'variance_percentage_june' => $variancePercentage,
                'trend_june'              => $rawDifference > 0 ? 'increase' : ($rawDifference < 0 ? 'decrease' : 'stable'),
            ];
        }

        $chartLabels = ["A.Y. {$previousAY}", "As of " . date('F Y')];
        $chartDatasets = [];
        foreach (self::TREND_DEPARTMENTS as $dept) {
            $chartDatasets[$dept] = [
                $historical[$dept][$previousAY]['major_count'] ?? 0,
                $currentYearViolations[$dept] ?? 0,
            ];
        }

        return [
            'departments_data' => $trendsData,
            'chart_labels'     => $chartLabels,
            'chart_datasets'   => $chartDatasets,
            'total_summary'    => [
                'total_population'          => array_sum(array_column($trendsData, 'total_population')),
                'total_violators_2023_2024' => array_sum(array_column($trendsData, 'violators_2023_2024')),
                'total_violators_june_2025' => array_sum(array_column($trendsData, 'violators_june_2025')),
                'total_current_violators'   => array_sum(array_column($trendsData, 'current_violators')),
                'total_variance_june'       => array_sum(array_column($trendsData, 'variance_june')),
            ],
        ];
    }

    /**
     * Get minor offense trends analysis data.
     */
    public function getMinorOffenseTrendsData(bool $includeDummyData = false): array
    {
        $historical = $this->getHistoricalData();
        [$previousAY, $comparisonAY] = $this->getHistoricalAcademicYears();
        $currentAYLabel = $this->getCurrentAcademicYearLabel();
        $currentYearViolations = $this->getCurrentYearViolations('minor');

        $minorOffensesData = [];
        foreach (self::TREND_DEPARTMENTS as $dept) {
            $prevData = $historical[$dept][$previousAY] ?? null;
            $compData = $historical[$dept][$comparisonAY] ?? null;

            $totalPopulation     = $compData['population'] ?? ($prevData['population'] ?? 0);
            $previousViolators   = $prevData['minor_count'] ?? 0;
            $comparisonViolators = $compData['minor_count'] ?? 0;
            $currentViolators    = $currentYearViolations[$dept] ?? 0;

            $rawDifference = $comparisonViolators - $previousViolators;
            $variancePercentage = $previousViolators > 0
                ? round((($previousViolators - $comparisonViolators) / $previousViolators) * 100, 2)
                : ($comparisonViolators > 0 ? -100.00 : 0.00);

            $currentPopulationPercentage = $totalPopulation > 0
                ? round(($comparisonViolators / $totalPopulation) * 100, 2)
                : 0;
            $previousPopulationPercentage = $totalPopulation > 0
                ? round(($previousViolators / $totalPopulation) * 100, 2)
                : 0;

            $minorOffensesData[$dept] = [
                'department'                    => $dept,
                'total_population'              => $totalPopulation,
                'violators_2023_2024'           => $previousViolators,
                'violators_june_2025'           => $comparisonViolators,
                'current_violators'             => $currentViolators,
                'variance'                      => $rawDifference,
                'variance_percentage'           => $variancePercentage,
                'trend'                         => $rawDifference > 0 ? 'increase' : ($rawDifference < 0 ? 'decrease' : 'stable'),
                'current_population_percentage' => $currentPopulationPercentage,
                'previous_population_percentage' => $previousPopulationPercentage,
            ];
        }

        return [
            'departments_data' => $minorOffensesData,
            'total_summary'    => [
                'total_population'          => array_sum(array_column($minorOffensesData, 'total_population')),
                'total_violators_2023_2024' => array_sum(array_column($minorOffensesData, 'violators_2023_2024')),
                'total_violators_june_2025' => array_sum(array_column($minorOffensesData, 'violators_june_2025')),
                'total_current_violators'   => array_sum(array_column($minorOffensesData, 'current_violators')),
                'total_variance'            => array_sum(array_column($minorOffensesData, 'variance')),
            ],
        ];
    }
}
