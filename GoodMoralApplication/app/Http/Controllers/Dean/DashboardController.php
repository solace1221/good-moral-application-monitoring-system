<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\GoodMoralApplication;
use App\Models\HistoricalViolationTrend;
use App\Models\StudentViolation;
use App\Models\Violation;
use App\Services\DashboardStatsService;
use Illuminate\Support\Facades\Auth;
use App\Traits\RoleCheck;
use App\Traits\DateFilterTrait;

class DashboardController extends Controller
{
  use RoleCheck, DateFilterTrait;

  protected DashboardStatsService $statsService;

  public function __construct(DashboardStatsService $statsService)
  {
    $this->statsService = $statsService;
  }

  public function dashboard(Request $request)
  {
    // User authentication already verified by login system
    $dean = Auth::user();
    $department = $dean->department;

    // Use service for department name variants (replaces 3x hardcoded $departmentMap)
    $possibleDepartments = DashboardStatsService::getPossibleDepartmentNames($department);

    // Get frequency filter from request
    $frequency = $request->get('frequency', 'all');

    // AY 2025-2026 boundaries — program-level data exists only for the current year.
    // Historical AYs (2023-2024, 2024-2025) were imported as dept-level aggregates.
    $currentAyStart = '2025-06-01 00:00:00';
    $currentAyEnd   = '2026-05-31 23:59:59';

    // Applications by Program — AY 2025-2026 only
    $courseList   = DashboardStatsService::getDepartmentCoursesWithNames($department);
    $courseCounts = [];
    foreach ($courseList as $code => $fullName) {
      $courseCounts[$code] = GoodMoralApplication::where('department', $department)
        ->whereIn('course_completed', [$code, $fullName])
        ->whereBetween('created_at', [$currentAyStart, $currentAyEnd])
        ->count();
    }

    // Build display arrays for the view (preserves abbr1/abbr2 card format)
    $allPrograms = DashboardStatsService::buildProgramDisplayArray($courseCounts);

    // Split into department-specific arrays for the view
    $SITEprograms = $department === 'SITE' ? $allPrograms : [];
    $SNAHSprograms = $department === 'SNAHS' ? $allPrograms : [];
    $SBAHMprograms = $department === 'SBAHM' ? $allPrograms : [];
    $SASTEprograms = $department === 'SASTE' ? $allPrograms : [];
    $SOMprograms = $department === 'SOM' ? $allPrograms : [];
    $GRADSCHprograms = $department === 'GRADSCH' ? $allPrograms : [];

    // Large departments get split into rows for the view
    $SBAHMfirstRow = array_slice($SBAHMprograms, 0, 4);
    $SBAHMsecondRow = array_slice($SBAHMprograms, 4, 5);
    $SASTEfirstRow = array_slice($SASTEprograms, 0, 4);
    $SASTEsecondRow = array_slice($SASTEprograms, 4, 5);
    $GRADSCHfirstRow = array_slice($GRADSCHprograms, 0, 4);
    $GRADSCHsecondRow = array_slice($GRADSCHprograms, 4, 3);

    // Select programs / rows for view based on department
    $programs = [];
    $programsRow1 = [];
    $programsRow2 = [];

    if (in_array($department, ['SITE', 'SNAHS', 'SOM'])) {
      $programs = $allPrograms;
    } else {
      $programsRow1 = array_slice($allPrograms, 0, 4);
      $programsRow2 = array_slice($allPrograms, 4);
    }

    // Violation stats from service (replaces inline queries)
    $vStats = $this->statsService->getViolationStats($frequency, $possibleDepartments);
    $minorpending = $vStats['minorPending'];
    $minorcomplied = $vStats['minorResolved'];
    $majorpending = $vStats['majorPending'];
    $majorcomplied = $vStats['majorResolved'];

    // Violations by Program — AY 2025-2026 only (historical AYs lack program-level breakdown)
    $allProgramsList = DashboardStatsService::getDepartmentCourses($department);

    $minorViolationsData = StudentViolation::query()->minor()
      ->whereIn('department', $possibleDepartments)
      ->whereBetween('created_at', [$currentAyStart, $currentAyEnd])
      ->selectRaw('course as program, COUNT(*) as count')
      ->groupBy('course')
      ->orderBy('count', 'desc')
      ->get();

    $majorViolationsData = StudentViolation::query()->major()
      ->whereIn('department', $possibleDepartments)
      ->whereBetween('created_at', [$currentAyStart, $currentAyEnd])
      ->selectRaw('course as program, COUNT(*) as count')
      ->groupBy('course')
      ->orderBy('count', 'desc')
      ->get();

    $minorViolationsByProgram = collect($allProgramsList)->map(function ($program) use ($minorViolationsData) {
      $existing = $minorViolationsData->firstWhere('program', $program);
      return (object) ['program' => $program, 'count' => $existing ? $existing->count : 0];
    })->sortByDesc('count');

    $majorViolationsByProgram = collect($allProgramsList)->map(function ($program) use ($majorViolationsData) {
      $existing = $majorViolationsData->firstWhere('program', $program);
      return (object) ['program' => $program, 'count' => $existing ? $existing->count : 0];
    })->sortByDesc('count');

    $allViolationsByProgram = StudentViolation::query()
      ->whereIn('department', $possibleDepartments)
      ->whereBetween('created_at', [$currentAyStart, $currentAyEnd])
      ->selectRaw('course as program, offense_type, COUNT(*) as count')
      ->groupBy('course', 'offense_type')
      ->orderBy('course')
      ->get()
      ->groupBy('program');

    // Percentages
    $minorTotal = $minorpending + $minorcomplied;
    $majorTotal = $majorpending + $majorcomplied;
    $minorResolvedPercentage = $minorTotal > 0 ? ($minorcomplied / $minorTotal) * 100 : 0;
    $majorResolvedPercentage = $majorTotal > 0 ? ($majorcomplied / $majorTotal) * 100 : 0;

    // Pending application counts
    $pendingGoodMoralApplications = $this->applyDateFilter(GoodMoralApplication::approvedByRegistrar()
      ->where('department', $department)
      ->where('certificate_type', 'good_moral')
      ->whereNotNull('application_status'), $frequency)->count();

    $pendingResidencyApplications = $this->applyDateFilter(GoodMoralApplication::approvedByRegistrar()
      ->where('department', $department)
      ->where('certificate_type', 'residency')
      ->whereNotNull('application_status'), $frequency)->count();

    $violationpage = Violation::paginate(10);

    // Summary card totals
    $totalApplications = $this->applyDateFilter(
      GoodMoralApplication::where('department', $department), $frequency
    )->count();

    $approvedByDean = $this->applyDateFilter(
      GoodMoralApplication::where('department', $department)
        ->where(function ($q) {
          $q->where('application_status', 'like', 'Approved by Dean%')
            ->orWhere('application_status', 'like', 'Receipt Uploaded%')
            ->orWhereIn('application_status', ['Ready for Moderator Print', 'Ready for Pickup', 'Claimed']);
        }),
      $frequency
    )->count();

    $rejectedByDean = $this->applyDateFilter(
      GoodMoralApplication::where('department', $department)
        ->where('application_status', 'like', 'Rejected by Dean%'),
      $frequency
    )->count();

    // Academic Year Violation Trends
    // AY 2023-2024 & AY 2024-2025 → historical_violation_trends (dept-level aggregates, no program breakdown)
    // AY 2025-2026 → live count from student_violations
    $deptRecord = Department::where('department_code', $department)->first();
    $histRows = $deptRecord
      ? HistoricalViolationTrend::where('department_id', $deptRecord->id)
          ->whereIn('academic_year', ['2023-2024', '2024-2025'])
          ->get()
          ->keyBy('academic_year')
      : collect();

    $hist2324 = $histRows->get('2023-2024');
    $hist2425 = $histRows->get('2024-2025');

    $minorTrend = [
      'AY 2023–2024' => $hist2324 ? (int) $hist2324->minor_count : 0,
      'AY 2024–2025' => $hist2425 ? (int) $hist2425->minor_count : 0,
      'AY 2025–2026' => StudentViolation::whereIn('department', $possibleDepartments)
        ->where('offense_type', 'minor')
        ->whereBetween('created_at', [$currentAyStart, $currentAyEnd])
        ->count(),
    ];
    $majorTrend = [
      'AY 2023–2024' => $hist2324 ? (int) $hist2324->major_count : 0,
      'AY 2024–2025' => $hist2425 ? (int) $hist2425->major_count : 0,
      'AY 2025–2026' => StudentViolation::whereIn('department', $possibleDepartments)
        ->where('offense_type', 'major')
        ->whereBetween('created_at', [$currentAyStart, $currentAyEnd])
        ->count(),
    ];
    $ayKeys = array_keys($minorTrend);
    $minorVariance = [];
    $majorVariance = [];
    for ($i = 1; $i < count($ayKeys); $i++) {
      $prev = $minorTrend[$ayKeys[$i - 1]];
      $curr = $minorTrend[$ayKeys[$i]];
      $minorVariance[$ayKeys[$i]] = $prev > 0
        ? round(($curr - $prev) / $prev * 100, 1)
        : ($curr > 0 ? 100.0 : 0.0);
      $prevM = $majorTrend[$ayKeys[$i - 1]];
      $currM = $majorTrend[$ayKeys[$i]];
      $majorVariance[$ayKeys[$i]] = $prevM > 0
        ? round(($currM - $prevM) / $prevM * 100, 1)
        : ($currM > 0 ? 100.0 : 0.0);
    }

    return view('dean.dashboard', compact(
      'minorpending',
      'minorcomplied',
      'majorpending',
      'majorcomplied',
      'department',
      'courseCounts',
      'minorViolationsByProgram',
      'majorViolationsByProgram',
      'allViolationsByProgram',
      'minorResolvedPercentage',
      'majorResolvedPercentage',
      'minorTotal',
      'majorTotal',
      'frequency',
      'pendingGoodMoralApplications',
      'pendingResidencyApplications',
      'totalApplications',
      'approvedByDean',
      'rejectedByDean',
      'minorTrend',
      'majorTrend',
      'minorVariance',
      'majorVariance'
    ) + [
      'frequencyOptions' => $this->getFrequencyOptions(),
      'frequencyLabel' => $this->getFrequencyLabel($frequency)
    ]);
  }

  public function major()
  {
    $possibleDepartments = DashboardStatsService::getPossibleDepartmentNames(Auth::user()->department);

    $students = StudentViolation::whereIn('department', $possibleDepartments)
      ->major()
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('dean.major', compact('students'));
  }

  public function minor()
  {
    $possibleDepartments = DashboardStatsService::getPossibleDepartmentNames(Auth::user()->department);

    $tab = request('tab', 'pending');

    $query = StudentViolation::whereIn('department', $possibleDepartments)
      ->minor()
      ->orderBy('created_at', 'desc');

    if ($tab === 'approved') {
      $query->where('status', 'Approved');
    } elseif ($tab === 'completed') {
      $query->whereIn('status', ['Complied', 'Closed']);
    } elseif ($tab === 'declined') {
      $query->where('status', 'Declined');
    } else {
      $query->whereIn('status', ['Reported', 'Under Review']);
    }

    $students = $query->paginate(10)->appends(request()->query());

    // Counts for tab badges
    $baseQuery = StudentViolation::whereIn('department', $possibleDepartments)->minor();
    $pendingCount = (clone $baseQuery)->whereIn('status', ['Reported', 'Under Review'])->count();
    $approvedCount = (clone $baseQuery)->where('status', 'Approved')->count();
    $completedCount = (clone $baseQuery)->whereIn('status', ['Complied', 'Closed'])->count();
    $declinedCount = (clone $baseQuery)->where('status', 'Declined')->count();

    return view('dean.minor', compact('students', 'tab', 'pendingCount', 'approvedCount', 'completedCount', 'declinedCount'));
  }
}
