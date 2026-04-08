<?php

namespace App\Services;

use App\Models\GoodMoralApplication;
use App\Models\StudentViolation;
use App\Models\ViolationNotif;
use App\Traits\DateFilterTrait;

class DashboardStatsService
{
    use DateFilterTrait;

    /**
     * Departments used across the system.
     */
    public const DEPARTMENTS = ['SITE', 'SASTE', 'SBAHM', 'SNAHS', 'SOM', 'GRADSCH'];

    /**
     * Get application counts per department with optional date filtering.
     */
    public function getApplicationCountsByDepartment(string $frequency = 'all'): array
    {
        $counts = [];
        foreach (self::DEPARTMENTS as $dept) {
            $counts[$dept] = $this->applyDateFilter(
                GoodMoralApplication::where('department', $dept),
                $frequency
            )->count();
        }
        return $counts;
    }

    /**
     * Get violation statistics (pending, resolved, percentages) for minor and major.
     *
     * @param string      $frequency           Date filter period
     * @param array|null  $departmentFilter     Optional department(s) to filter by
     */
    public function getViolationStats(string $frequency = 'all', ?array $departmentFilter = null): array
    {
        $buildQuery = function (string $offenseType, string $statusCondition) use ($frequency, $departmentFilter) {
            $query = StudentViolation::where('offense_type', $offenseType);
            if ($departmentFilter) {
                $query->whereIn('department', $departmentFilter);
            }
            if ($statusCondition === 'pending') {
                $query->where('status', '!=', 2);
            } else {
                $query->where('status', '=', 2);
            }
            return $this->applyDateFilter($query, $frequency)->count();
        };

        $minorPending = $buildQuery('minor', 'pending');
        $minorResolved = $buildQuery('minor', 'resolved');
        $majorPending = $buildQuery('major', 'pending');
        $majorResolved = $buildQuery('major', 'resolved');

        $minorTotal = $minorPending + $minorResolved;
        $majorTotal = $majorPending + $majorResolved;

        return [
            'minorPending' => $minorPending,
            'minorResolved' => $minorResolved,
            'minorTotal' => $minorTotal,
            'minorResolvedPercentage' => $minorTotal > 0 ? round(($minorResolved / $minorTotal) * 100, 1) : 0,
            'majorPending' => $majorPending,
            'majorResolved' => $majorResolved,
            'majorTotal' => $majorTotal,
            'majorResolvedPercentage' => $majorTotal > 0 ? round(($majorResolved / $majorTotal) * 100, 1) : 0,
        ];
    }

    /**
     * Get violation counts per department, broken down by offense type.
     *
     * @param string     $frequency       Date filter period
     * @param array|null $departments     Departments to include (defaults to all)
     */
    public function getViolationsByDepartment(string $frequency = 'all', ?array $departments = null): array
    {
        $departments = $departments ?? self::DEPARTMENTS;
        $major = [];
        $minor = [];

        foreach ($departments as $dept) {
            $major[$dept] = $this->applyDateFilter(
                StudentViolation::where('offense_type', 'major')->where('department', $dept),
                $frequency
            )->count();

            $minor[$dept] = $this->applyDateFilter(
                StudentViolation::where('offense_type', 'minor')->where('department', $dept),
                $frequency
            )->count();
        }

        return [
            'majorViolationsByDept' => $major,
            'minorViolationsByDept' => $minor,
        ];
    }

    /**
     * Get escalation notifications (students with 3+ minor violations).
     */
    public function getEscalationNotifications(int $limit = 5)
    {
        return ViolationNotif::where(function ($q) {
            $q->where('notif', 'LIKE', '%3 minor violations%')
              ->orWhere('notif', 'LIKE', '%escalated to major%');
        })
        ->orderBy('created_at', 'desc')
        ->take($limit)
        ->get();
    }
}
