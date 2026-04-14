<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GoodMoralApplication;
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

    // Get per-course application counts from service (replaces 37 individual queries)
    $courseCounts = $this->statsService->getApplicationCountsByCourse($department, $frequency);

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

    // Violations by program for charts (uses config-driven course list)
    $allProgramsList = DashboardStatsService::getDepartmentCourses($department);

    $minorViolationsQuery = StudentViolation::query()->minor()
      ->whereIn('department', $possibleDepartments);
    $minorViolationsData = $this->applyDateFilter($minorViolationsQuery, $frequency)
      ->selectRaw('course as program, COUNT(*) as count')
      ->groupBy('course')
      ->orderBy('count', 'desc')
      ->get();

    $majorViolationsQuery = StudentViolation::query()->major()
      ->whereIn('department', $possibleDepartments);
    $majorViolationsData = $this->applyDateFilter($majorViolationsQuery, $frequency)
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

    $allViolationsByProgram = $this->applyDateFilter(StudentViolation::query()->whereIn('department', $possibleDepartments), $frequency)
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

    // Recent violations
    $recentViolations = StudentViolation::whereIn('department', $possibleDepartments)
      ->orderBy('created_at', 'desc')
      ->take(10)
      ->get();

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
    return view('dean.dashboard', compact(
      'minorpending',
      'minorcomplied',
      'majorpending',
      'majorcomplied',
      'violationpage',
      'SITEprograms',
      'SNAHSprograms',
      'SBAHMprograms',
      'SASTEprograms',
      'SOMprograms',
      'GRADSCHprograms',
      'SBAHMfirstRow',
      'SBAHMsecondRow',
      'SASTEfirstRow',
      'SASTEsecondRow',
      'GRADSCHfirstRow',
      'GRADSCHsecondRow',
      'department',
      'programs',
      'programsRow1',
      'programsRow2',
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
      'recentViolations'
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

    $students = StudentViolation::whereIn('department', $possibleDepartments)
      ->minor()
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('dean.minor', compact('students'));
  }
}
