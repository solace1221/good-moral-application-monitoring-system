<?php

namespace App\Traits;

use Carbon\Carbon;

trait DateFilterTrait
{
    /**
     * Get date range based on frequency filter
     *
     * @param string $frequency
     * @return array
     */
    protected function getDateRange($frequency = 'all')
    {
        $now = Carbon::now();
        
        switch ($frequency) {
            case 'daily':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
                
            case 'weekly':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
                
            case 'monthly':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];

            case 'annually':
            case 'yearly':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];

            case 'first_semester':
                // First semester: September to January
                $currentYear = $now->year;
                // If we're in Feb-Aug, use previous year's first semester
                if ($now->month < 9) {
                    $currentYear--;
                }
                return [
                    'start' => Carbon::create($currentYear, 9, 1)->startOfDay(),
                    'end' => Carbon::create($currentYear + 1, 1, 31)->endOfDay()
                ];

            case 'second_semester':
                // Second semester: February to June
                $currentYear = $now->year;
                // If we're in Jul-Jan, use appropriate year's second semester
                if ($now->month < 2) {
                    $currentYear--;
                } elseif ($now->month > 6) {
                    $currentYear++;
                }
                return [
                    'start' => Carbon::create($currentYear, 2, 1)->startOfDay(),
                    'end' => Carbon::create($currentYear, 6, 30)->endOfDay()
                ];

            case 'summer_term':
                // Summer term: July to August
                $currentYear = $now->year;
                return [
                    'start' => Carbon::create($currentYear, 7, 1)->startOfDay(),
                    'end' => Carbon::create($currentYear, 8, 31)->endOfDay()
                ];

            default:
                // Handle specific month filtering (monthly_1, monthly_2, etc.)
                if (strpos($frequency, 'monthly_') === 0) {
                    $month = (int) str_replace('monthly_', '', $frequency);
                    if ($month >= 1 && $month <= 12) {
                        $year = $now->year;
                        return [
                            'start' => Carbon::create($year, $month, 1)->startOfMonth(),
                            'end' => Carbon::create($year, $month, 1)->endOfMonth()
                        ];
                    }
                }

                // Default case for 'all' and unknown frequencies
                return [
                    'start' => null,
                    'end' => null
                ];
        }
    }

    /**
     * Apply date filter to query
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model $query
     * @param string $frequency
     * @param string $dateColumn
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    protected function applyDateFilter($query, $frequency = 'all', $dateColumn = 'created_at')
    {
        $dateRange = $this->getDateRange($frequency);
        
        if ($dateRange['start'] && $dateRange['end']) {
            $query->whereBetween($dateColumn, [$dateRange['start'], $dateRange['end']]);
        }
        
        return $query;
    }

    /**
     * Get frequency options for dropdown
     *
     * @return array
     */
    protected function getFrequencyOptions()
    {
        return [
            'all' => 'All Time',
            'daily' => 'Today',
            'weekly' => 'This Week',
            'monthly' => 'This Month',
            'yearly' => 'This Year',
            'first_semester' => 'First Semester (Sep-Jan)',
            'second_semester' => 'Second Semester (Feb-Jun)',
            'summer_term' => 'Summer Term (Jul-Aug)'
        ];
    }

    /**
     * Get current frequency label
     *
     * @param string $frequency
     * @return string
     */
    protected function getFrequencyLabel($frequency = 'all')
    {
        $options = $this->getFrequencyOptions();
        return $options[$frequency] ?? 'All Time';
    }

    /**
     * Get detailed time period description for reports
     *
     * @param string $frequency
     * @return array
     */
    protected function getTimePeriodDescription($frequency = 'all')
    {
        $now = Carbon::now();
        $dateRange = $this->getDateRange($frequency);

        switch ($frequency) {
            case 'daily':
                return [
                    'title' => 'Daily Report',
                    'period' => $now->format('F j, Y'),
                    'description' => 'Data for ' . $now->format('l, F j, Y'),
                    'short' => $now->format('M j, Y')
                ];

            case 'monthly':
                return [
                    'title' => 'Monthly Report',
                    'period' => $now->format('F Y'),
                    'description' => 'Data for ' . $now->format('F Y'),
                    'short' => $now->format('M Y')
                ];

            case 'yearly':
                return [
                    'title' => 'Yearly Report',
                    'period' => $now->format('Y'),
                    'description' => 'Data for Calendar Year ' . $now->format('Y'),
                    'short' => $now->format('Y')
                ];

            case 'first_semester':
                $currentYear = $now->year;
                if ($now->month < 9) {
                    $currentYear--;
                }
                return [
                    'title' => 'First Semester Report',
                    'period' => 'September ' . $currentYear . ' - January ' . ($currentYear + 1),
                    'description' => 'First Semester (September ' . $currentYear . ' to January ' . ($currentYear + 1) . ')',
                    'short' => '1st Sem ' . $currentYear . '-' . ($currentYear + 1)
                ];

            case 'second_semester':
                $currentYear = $now->year;
                if ($now->month < 2) {
                    $currentYear--;
                } elseif ($now->month > 6) {
                    $currentYear++;
                }
                return [
                    'title' => 'Second Semester Report',
                    'period' => 'February ' . $currentYear . ' - June ' . $currentYear,
                    'description' => 'Second Semester (February ' . $currentYear . ' to June ' . $currentYear . ')',
                    'short' => '2nd Sem ' . $currentYear
                ];

            case 'summer_term':
                $currentYear = $now->year;
                return [
                    'title' => 'Summer Term Report',
                    'period' => 'July ' . $currentYear . ' - August ' . $currentYear,
                    'description' => 'Summer Term (July ' . $currentYear . ' to August ' . $currentYear . ')',
                    'short' => 'Summer ' . $currentYear
                ];

            default: // 'all' and other cases
                return [
                    'title' => 'Complete Report',
                    'period' => 'All Time',
                    'description' => 'Complete data for all time periods',
                    'short' => 'All Time'
                ];
        }
    }

    /**
     * Get specific date range description for a given time period
     *
     * @param string $frequency
     * @param Carbon|null $referenceDate
     * @return array
     */
    protected function getSpecificTimePeriodDescription($frequency = 'all', $referenceDate = null)
    {
        $date = $referenceDate ?? Carbon::now();

        switch ($frequency) {
            case 'daily':
                return [
                    'title' => 'Daily Report - ' . $date->format('F j, Y'),
                    'period' => $date->format('F j, Y'),
                    'description' => 'Applications and violations for ' . $date->format('l, F j, Y'),
                    'short' => $date->format('M j, Y'),
                    'filename_suffix' => $date->format('Y-m-d')
                ];

            case 'monthly':
                return [
                    'title' => 'Monthly Report - ' . $date->format('F Y'),
                    'period' => $date->format('F Y'),
                    'description' => 'Applications and violations for ' . $date->format('F Y'),
                    'short' => $date->format('M Y'),
                    'filename_suffix' => $date->format('Y-m')
                ];

            case 'yearly':
                return [
                    'title' => 'Yearly Report - ' . $date->format('Y'),
                    'period' => 'Calendar Year ' . $date->format('Y'),
                    'description' => 'Applications and violations for Calendar Year ' . $date->format('Y'),
                    'short' => $date->format('Y'),
                    'filename_suffix' => $date->format('Y')
                ];

            case 'first_semester':
                $currentYear = $date->year;
                if ($date->month < 9) {
                    $currentYear--;
                }
                return [
                    'title' => 'First Semester Report - A.Y. ' . $currentYear . '-' . ($currentYear + 1),
                    'period' => 'September ' . $currentYear . ' - January ' . ($currentYear + 1),
                    'description' => 'First Semester A.Y. ' . $currentYear . '-' . ($currentYear + 1) . ' (September ' . $currentYear . ' to January ' . ($currentYear + 1) . ')',
                    'short' => '1st Sem ' . $currentYear . '-' . ($currentYear + 1),
                    'filename_suffix' => 'first-sem-' . $currentYear . '-' . ($currentYear + 1)
                ];

            case 'second_semester':
                $currentYear = $date->year;
                if ($date->month < 2) {
                    $currentYear--;
                } elseif ($date->month > 6) {
                    $currentYear++;
                }
                return [
                    'title' => 'Second Semester Report - A.Y. ' . ($currentYear - 1) . '-' . $currentYear,
                    'period' => 'February ' . $currentYear . ' - June ' . $currentYear,
                    'description' => 'Second Semester A.Y. ' . ($currentYear - 1) . '-' . $currentYear . ' (February ' . $currentYear . ' to June ' . $currentYear . ')',
                    'short' => '2nd Sem ' . ($currentYear - 1) . '-' . $currentYear,
                    'filename_suffix' => 'second-sem-' . ($currentYear - 1) . '-' . $currentYear
                ];

            case 'summer_term':
                $currentYear = $date->year;
                return [
                    'title' => 'Summer Term Report - A.Y. ' . ($currentYear - 1) . '-' . $currentYear,
                    'period' => 'July ' . $currentYear . ' - August ' . $currentYear,
                    'description' => 'Summer Term A.Y. ' . ($currentYear - 1) . '-' . $currentYear . ' (July ' . $currentYear . ' to August ' . $currentYear . ')',
                    'short' => 'Summer ' . ($currentYear - 1) . '-' . $currentYear,
                    'filename_suffix' => 'summer-' . ($currentYear - 1) . '-' . $currentYear
                ];

            default:
                // Handle specific month filtering (monthly_1, monthly_2, etc.)
                if (strpos($frequency, 'monthly_') === 0) {
                    $month = (int) str_replace('monthly_', '', $frequency);
                    if ($month >= 1 && $month <= 12) {
                        $year = $referenceDate ? $referenceDate->year : Carbon::now()->year;
                        $monthDate = Carbon::create($year, $month, 1);
                        return [
                            'title' => 'Monthly Report - ' . $monthDate->format('F Y'),
                            'period' => $monthDate->format('F Y'),
                            'description' => 'Applications and violations for ' . $monthDate->format('F Y'),
                            'short' => $monthDate->format('M Y'),
                            'filename_suffix' => $monthDate->format('Y-m')
                        ];
                    }
                }

                // Default case for 'all' and unknown frequencies
                return [
                    'title' => 'Complete Report - All Time',
                    'period' => 'All Time',
                    'description' => 'Complete data for all time periods',
                    'short' => 'All Time',
                    'filename_suffix' => 'all-time'
                ];
        }
    }
}
