<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardStatsService;
use App\Models\ViolationNotif;
use App\Models\Violation;
use App\Traits\DateFilterTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
  use DateFilterTrait;

  protected DashboardStatsService $statsService;

  public function __construct(
    DashboardStatsService $statsService
  ) {
    $this->statsService = $statsService;
  }

  public function dashboard(Request $request)
  {
    // Check if user is authenticated
    if (!Auth::check()) {
      return redirect()->route('login');
    }

    // User authentication already verified by login system

    // Get frequency filter from request
    $frequency = $request->get('frequency', 'all');

    // Use DashboardStatsService for stats
    $deptCounts = $this->statsService->getApplicationCountsByDepartment($frequency);
    $site = $deptCounts['SITE'];
    $saste = $deptCounts['SASTE'];
    $sbahm = $deptCounts['SBAHM'];
    $snahs = $deptCounts['SNAHS'];
    $som = $deptCounts['SOM'];
    $gradsch = $deptCounts['GRADSCH'];

    $vStats = $this->statsService->getViolationStats($frequency);
    $minorpending = $vStats['minorPending'];
    $minorcomplied = $vStats['minorResolved'];
    $majorpending = $vStats['majorPending'];
    $majorcomplied = $vStats['majorResolved'];

    // Percentages for minor offenses
    $totalMinor = $vStats['minorTotal'];
    $pendingPercent = $totalMinor > 0 ? ($minorpending / $totalMinor) * 100 : 0;
    $compliedPercent = 100 - $pendingPercent;
    $dashArray = $pendingPercent . ' ' . $compliedPercent;

    // Percentages for major offenses
    $totalMajor = $vStats['majorTotal'];
    $majorPendingPercent = $totalMajor > 0 ? ($majorpending / $totalMajor) * 100 : 0;
    $majorCompliedPercent = 100 - $majorPendingPercent;
    $majorDashArray = $majorPendingPercent . ' ' . $majorCompliedPercent;

    // Departments array for looping
    $departments = DashboardStatsService::DEPARTMENTS;

    // Violation counts by department
    $deptViolations = $this->statsService->getViolationsByDepartment($frequency);
    $majorCounts = $deptViolations['majorViolationsByDept'];
    $minorCounts = $deptViolations['minorViolationsByDept'];
    $majorViolationsByDept = $majorCounts;
    $minorViolationsByDept = $minorCounts;

    // Totals and percentages
    $minorTotal = $vStats['minorTotal'];
    $majorTotal = $vStats['majorTotal'];
    $minorResolved = $vStats['minorResolved'];
    $minorPending = $vStats['minorPending'];
    $majorResolved = $vStats['majorResolved'];
    $majorPending = $vStats['majorPending'];
    $minorResolvedPercentage = $vStats['minorResolvedPercentage'];
    $majorResolvedPercentage = $vStats['majorResolvedPercentage'];

    // Pagination
    $violationpage = Violation::paginate(10);

    // Get escalation notifications for admin
    $currentAdmin = Auth::user();
    $escalationNotifications = ViolationNotif::where('student_id', $currentAdmin->student_id)
      ->where('ref_num', 'LIKE', 'ESCALATION-%')
      ->where('status', 0) // Unread
      ->orderBy('created_at', 'desc')
      ->limit(5)
      ->get();

    // Get trends analysis data for major offenses
    $trendsData = $this->getTrendsAnalysisData();

    // Get minor offenses trends data
    $minorOffensesData = $this->getMinorOffensesTrendsData();

    // Get authenticated admin data
    $admin = Auth::user();

    // Pass all to view
    return view('admin.dashboard', compact(
      'admin',
      'site',
      'sbahm',
      'saste',
      'snahs',
      'som',
      'gradsch',
      'minorpending',
      'minorcomplied',
      'majorpending',
      'majorcomplied',
      'pendingPercent',
      'compliedPercent',
      'dashArray',
      'majorPendingPercent',
      'majorCompliedPercent',
      'majorDashArray',
      'violationpage',
      'departments',
      'majorCounts',
      'minorCounts',
      'majorViolationsByDept',
      'minorViolationsByDept',
      'minorTotal',
      'majorTotal',
      'minorResolved',
      'minorPending',
      'majorResolved',
      'majorPending',
      'minorResolvedPercentage',
      'majorResolvedPercentage',
      'frequency',
      'escalationNotifications',
      'trendsData',
      'minorOffensesData'
    ) + [
      'frequencyOptions' => $this->getFrequencyOptions(),
      'frequencyLabel' => $this->getFrequencyLabel($frequency)
    ]);
  }

  /**
   * Get trends analysis data for major offenses by department
   * This method now dynamically calculates data from actual violations in the database
   */
  private function getTrendsAnalysisData()
  {
    // Get current academic year and previous academic year data
    $currentYear = date('Y');
    $previousYear = $currentYear - 1;
    
    // Define academic year periods
    $previousAcademicYearStart = "{$previousYear}-08-01"; // Aug 2023
    $previousAcademicYearEnd = "{$currentYear}-07-31";   // Jul 2024
    $currentAcademicYearStart = "{$currentYear}-08-01";  // Aug 2024
    $currentDate = date('Y-m-d');
    
    // Get all departments
    $departments = ['SITE', 'SBAHM', 'SNAHS', 'SASTE'];
    
    // Define population totals as per requirements - ALWAYS use these values
    $departmentPopulation = [
      'SITE' => 640,
      'SBAHM' => 727, 
      'SNAHS' => 2831,
      'SASTE' => 409
    ];
    
    // Define Previous AY (AY 2023-2024) Major Offenses data - Official numbers
    $previousYearViolations = [
      'SITE' => 9,
      'SBAHM' => 15,
      'SNAHS' => 79,
      'SASTE' => 4
    ];
    
    // Define AY 2024-2025 dummy data for testing (will be replaced with real data)
    $currentYearDummyViolations = [
      'SITE' => 6,    // AY 2024-2025 dummy data
      'SBAHM' => 10,  // AY 2024-2025 dummy data
      'SNAHS' => 60,  // AY 2024-2025 dummy data
      'SASTE' => 3    // AY 2024-2025 dummy data
    ];
    
    // Get violation data for current academic year (major offenses only) - Real-time from database for AY 2025-2026
    $currentYearViolations = \App\Models\StudentViolation::where('offense_type', 'major')
      ->where('created_at', '>=', $currentAcademicYearStart)
      ->where('created_at', '<=', $currentDate . ' 23:59:59')
      ->selectRaw('department, count(distinct student_id) as violator_count')
      ->groupBy('department')
      ->pluck('violator_count', 'department')
      ->toArray();

    $trendsData = [];
    foreach ($departments as $dept) {
      $totalPopulation = $departmentPopulation[$dept] ?? 0;
      $previousViolators = $previousYearViolations[$dept] ?? 0;
      $currentViolators = $currentYearViolations[$dept] ?? 0;
      $dummyViolators = $currentYearDummyViolations[$dept] ?? 0; // AY 2024-2025 dummy data
      
      // Calculate variance using AY 2023-2024 vs AY 2024-2025 (dummy data)
      $rawDifference = $dummyViolators - $previousViolators;
      $variancePercentage = $previousViolators > 0
        ? round((($previousViolators - $dummyViolators) / $previousViolators) * 100, 2)
        : ($dummyViolators > 0 ? -100.00 : 0.00);

      $trendsData[$dept] = [
        'department' => $dept,
        'total_population' => $totalPopulation,
        'violators_2023_2024' => $previousViolators,
        'violators_june_2025' => $currentYearDummyViolations[$dept] ?? 0, // AY 2024-2025 dummy data
        'current_violators' => $currentViolators, // AY 2025-2026 (real database data)
        'variance_june' => $rawDifference,
        'variance_percentage_june' => $variancePercentage,
        'trend_june' => $rawDifference > 0 ? 'increase' : ($rawDifference < 0 ? 'decrease' : 'stable')
      ];
    }

    // Create chart data for visualization
    $chartLabels = ["A.Y. {$previousYear}-{$currentYear}", "As of " . date('F Y')];
    $chartDatasets = [];

    foreach ($departments as $dept) {
      $chartDatasets[$dept] = [
        $previousYearViolations[$dept] ?? 0,
        $currentYearViolations[$dept] ?? 0
      ];
    }

    return [
      'departments_data' => $trendsData,
      'chart_labels' => $chartLabels,
      'chart_datasets' => $chartDatasets,
      'total_summary' => [
        'total_population' => array_sum(array_column($trendsData, 'total_population')),
        'total_violators_2023_2024' => array_sum(array_column($trendsData, 'violators_2023_2024')),
        'total_violators_june_2025' => array_sum(array_column($trendsData, 'violators_june_2025')),
        'total_current_violators' => array_sum(array_column($trendsData, 'current_violators')),
        'total_variance_june' => array_sum(array_column($trendsData, 'variance_june'))
      ]
    ];
  }

  /**
   * Get minor offenses trends analysis data
   * This method dynamically calculates data from actual minor violations in the database
   */
  private function getMinorOffensesTrendsData()
  {
    // Get current academic year and previous academic year data
    $currentYear = date('Y');
    $previousYear = $currentYear - 1;
    
    // Define academic year periods
    $previousAcademicYearStart = "{$previousYear}-08-01"; // Aug 2023
    $previousAcademicYearEnd = "{$currentYear}-07-31";   // Jul 2024
    $currentAcademicYearStart = "{$currentYear}-08-01";  // Aug 2024
    $currentDate = date('Y-m-d');
    
    // Get all departments
    $departments = ['SITE', 'SBAHM', 'SNAHS', 'SASTE'];
    
    // Define population totals as per requirements - ALWAYS use these values
    $departmentPopulation = [
      'SITE' => 640,
      'SBAHM' => 727, 
      'SNAHS' => 2831,
      'SASTE' => 409
    ];
    
    // Define Previous AY (AY 2023-2024) Minor Offenses data - Official numbers
    $previousYearViolations = [
      'SITE' => 118,
      'SBAHM' => 88,
      'SNAHS' => 524,
      'SASTE' => 97
    ];
    
    // Define AY 2024-2025 dummy data for testing (will be replaced with real data)
    $currentYearDummyViolations = [
      'SITE' => 90,   // AY 2024-2025 dummy data
      'SBAHM' => 75,  // AY 2024-2025 dummy data
      'SNAHS' => 420, // AY 2024-2025 dummy data
      'SASTE' => 70   // AY 2024-2025 dummy data
    ];
    
    // Get violation data for current academic year (minor offenses only) - Real-time from database for AY 2025-2026
    $currentYearViolations = \App\Models\StudentViolation::where('offense_type', 'minor')
      ->where('created_at', '>=', $currentAcademicYearStart)
      ->where('created_at', '<=', $currentDate . ' 23:59:59')
      ->selectRaw('department, count(distinct student_id) as violator_count')
      ->groupBy('department')
      ->pluck('violator_count', 'department')
      ->toArray();

    $minorOffensesData = [];
    foreach ($departments as $dept) {
      $totalPopulation = $departmentPopulation[$dept] ?? 0;
      $previousViolators = $previousYearViolations[$dept] ?? 0;
      $currentViolators = $currentYearViolations[$dept] ?? 0;
      $dummyViolators = $currentYearDummyViolations[$dept] ?? 0; // AY 2024-2025 dummy data
      
      // Calculate variance using AY 2023-2024 vs AY 2024-2025 (dummy data)
      $rawDifference = $dummyViolators - $previousViolators;
      $variancePercentage = $previousViolators > 0
        ? round((($previousViolators - $dummyViolators) / $previousViolators) * 100, 2)
        : ($dummyViolators > 0 ? -100.00 : 0.00);

      // Calculate percentage of population affected
      $currentPopulationPercentage = $totalPopulation > 0
        ? round(($dummyViolators / $totalPopulation) * 100, 2) // Use dummy data for AY 2024-2025
        : 0;
      $previousPopulationPercentage = $totalPopulation > 0
        ? round(($previousViolators / $totalPopulation) * 100, 2)
        : 0;

      $minorOffensesData[$dept] = [
        'department' => $dept,
        'total_population' => $totalPopulation,
        'violators_2023_2024' => $previousViolators,
        'violators_june_2025' => $dummyViolators, // AY 2024-2025 dummy data
        'current_violators' => $currentViolators, // AY 2025-2026 (real database data)
        'variance' => $rawDifference,
        'variance_percentage' => $variancePercentage,
        'trend' => $rawDifference > 0 ? 'increase' : ($rawDifference < 0 ? 'decrease' : 'stable'),
        'current_population_percentage' => $currentPopulationPercentage,
        'previous_population_percentage' => $previousPopulationPercentage
      ];
    }

    return [
      'departments_data' => $minorOffensesData,
      'total_summary' => [
        'total_population' => array_sum(array_column($minorOffensesData, 'total_population')),
        'total_violators_2023_2024' => array_sum(array_column($minorOffensesData, 'violators_2023_2024')),
        'total_violators_june_2025' => array_sum(array_column($minorOffensesData, 'violators_june_2025')),
        'total_current_violators' => array_sum(array_column($minorOffensesData, 'current_violators')),
        'total_variance' => array_sum(array_column($minorOffensesData, 'variance'))
      ]
    ];
  }

  /**
   * Parse course_year field to extract course code and year level
   * Examples: "BSIT 1st Year" -> ['course' => 'BSIT', 'year_level' => '1st Year']
   *           "BSN 2nd Year" -> ['course' => 'BSN', 'year_level' => '2nd Year']
   *           "BS Psych 3rd Year" -> ['course' => 'BS Psych', 'year_level' => '3rd Year']
   */
  private function parseCourseYear($courseYearString)
  {
    if (empty($courseYearString)) {
      return ['course' => null, 'year_level' => null];
    }

    $courseYearString = trim($courseYearString);

    // Common year level patterns
    $yearPatterns = [
      '1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year',
      'First Year', 'Second Year', 'Third Year', 'Fourth Year', 'Fifth Year',
      'Graduate', 'Graduated'
    ];

    $course = null;
    $yearLevel = null;

    // Try to find year level pattern in the string
    foreach ($yearPatterns as $pattern) {
      if (stripos($courseYearString, $pattern) !== false) {
        $yearLevel = $pattern;
        // Extract course by removing the year level part
        $course = trim(str_ireplace($pattern, '', $courseYearString));
        break;
      }
    }

    // If no year pattern found, try to extract using common separators
    if (!$yearLevel) {
      // Try patterns like "BSIT-1st", "BSIT_1st", "BSIT 1st"
      if (preg_match('/^(.+?)[\s\-_]+(\d+(?:st|nd|rd|th)?\s*(?:year|yr)?)/i', $courseYearString, $matches)) {
        $course = trim($matches[1]);
        $yearLevel = trim($matches[2]);

        // Normalize year level format
        $yearLevel = preg_replace('/(\d+)(st|nd|rd|th)?\s*(year|yr)?/i', '$1$2 Year', $yearLevel);
      } else {
        // If no clear pattern, assume the whole string is the course
        $course = $courseYearString;
        $yearLevel = null;
      }
    }

    // Clean up course name
    if ($course) {
      $course = trim($course);
      // Remove common separators at the end
      $course = rtrim($course, ' -_');
    }

    return [
      'course' => $course ?: null,
      'year_level' => $yearLevel ?: null
    ];
  }
}
