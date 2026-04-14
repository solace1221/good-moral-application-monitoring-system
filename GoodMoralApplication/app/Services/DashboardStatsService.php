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
     * Departments that participate in violation tracking (excludes SOM/GRADSCH).
     */
    public const VIOLATION_DEPARTMENTS = ['SITE', 'SASTE', 'SBAHM', 'SNAHS'];

    /**
     * Department abbreviation → possible full names used in violation records.
     */
    public const DEPARTMENT_MAP = [
        'SITE' => ['SITE', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING'],
        'SNAHS' => ['SNAHS', 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES'],
        'SBAHM' => ['SBAHM', 'SCHOOL OF BUSINESS ADMINISTRATION AND HOSPITALITY MANAGEMENT'],
        'SASTE' => ['SASTE', 'SCHOOL OF ARTS, SCIENCES, TEACHER EDUCATION'],
        'SOM' => ['SOM', 'SCHOOL OF MEDICINE'],
        'GRADSCH' => ['GRADSCH', 'GRADUATE SCHOOL'],
    ];

    /**
     * Get possible department name variants for a given abbreviation.
     */
    public static function getPossibleDepartmentNames(string $abbreviation): array
    {
        return self::DEPARTMENT_MAP[$abbreviation] ?? [$abbreviation];
    }

    /**
     * Get the courses for a given department from config.
     */
    public static function getDepartmentCourses(string $department): array
    {
        $deptConfig = config("courses.departments.{$department}");
        return $deptConfig ? array_keys($deptConfig['courses']) : [];
    }

    /**
     * Get courses with their full names for a department.
     */
    public static function getDepartmentCoursesWithNames(string $department): array
    {
        $deptConfig = config("courses.departments.{$department}");
        return $deptConfig ? $deptConfig['courses'] : [];
    }

    /**
     * Get application counts per course within a department, with date filtering.
     * Replaces 37+ individual hardcoded queries in Dean\DashboardController.
     */
    public function getApplicationCountsByCourse(string $department, string $frequency = 'all'): array
    {
        $courses = self::getDepartmentCoursesWithNames($department);
        $counts = [];

        foreach ($courses as $code => $fullName) {
            $counts[$code] = $this->applyDateFilter(
                GoodMoralApplication::where('department', $department)
                    ->whereIn('course_completed', [$code, $fullName]),
                $frequency
            )->count();
        }

        return $counts;
    }

    /**
     * Course code → display abbreviation pairs for the dean dashboard cards.
     * Each entry: [abbr1, abbr2].
     */
    public const COURSE_DISPLAY_ABBR = [
        'BSIT' => ['BS', 'IT'], 'BLIS' => ['BL', 'IS'], 'BSCE' => ['BS', 'CE'],
        'BS CpE' => ['BS', 'CpE'], 'BS ENSE' => ['BS', 'ENSE'],
        'BSN' => ['BS', 'N'], 'BSPh' => ['BS', 'Ph'], 'BSMT' => ['BS', 'MT'],
        'BSPT' => ['BS', 'PT'], 'BSRT' => ['BS', 'RT'],
        'BSA' => ['BS', 'A'], 'BSE' => ['BS', 'E'],
        'BSBAMM' => ['BSBA', 'MM'], 'BSBA MFM' => ['BSBA', 'MFM'],
        'BSBA MOP' => ['BSBA', 'MOP'], 'BSMA' => ['BS', 'MA'],
        'BSHM' => ['BS', 'HM'], 'BSTM' => ['BS', 'TM'], 'BSPDMI' => ['BS', 'PDMI'],
        'BAELS' => ['BA', 'ELS'], 'BS Psych' => ['BS', 'Psych'],
        'BS Bio' => ['BS', 'Bio'], 'BSSW' => ['BS', 'SW'],
        'BSPA' => ['BS', 'PA'], 'BS Bio MB' => ['BS', 'Bio MB'],
        'BSEd' => ['BS', 'Ed'], 'BEEd' => ['BE', 'Ed'], 'BPEd' => ['B', 'PEd'],
        'MD' => ['M', 'D'], 'BS Med' => ['BS', 'Med'],
        'MBA' => ['M', 'BA'], 'MPA' => ['M', 'PA'], 'MEd' => ['M', 'Ed'],
        'MS' => ['M', 'S'], 'MA' => ['M', 'A'], 'PhD' => ['Ph', 'D'], 'EdD' => ['Ed', 'D'],
    ];

    /**
     * Build program display arrays for the dean dashboard.
     * Returns arrays with 'abbr1', 'abbr2', 'count' keys matching the view's expected format.
     *
     * @param  array $courseCounts  Course code → count from getApplicationCountsByCourse()
     * @return array  Each element: ['abbr1' => ..., 'abbr2' => ..., 'count' => ...]
     */
    public static function buildProgramDisplayArray(array $courseCounts): array
    {
        $programs = [];
        foreach ($courseCounts as $code => $count) {
            $abbr = self::COURSE_DISPLAY_ABBR[$code] ?? [substr($code, 0, 2), substr($code, 2)];
            $programs[] = ['abbr1' => $abbr[0], 'abbr2' => $abbr[1], 'count' => $count];
        }
        return $programs;
    }

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
                $query->where('status', '0');
            } else {
                $query->where('status', '2');
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
        return ViolationNotif::where('ref_num', 'LIKE', 'ESCALATION-%')
        ->where('student_id', 'ROLE_ADMIN')
        ->orderBy('created_at', 'desc')
        ->take($limit)
        ->get();
    }
}
