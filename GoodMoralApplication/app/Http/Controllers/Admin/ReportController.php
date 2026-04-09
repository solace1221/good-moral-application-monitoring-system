<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateReportRequest;
use App\Models\AcademicYear;
use App\Models\GeneratedReport;
use App\Models\GoodMoralApplication;
use App\Models\RoleAccount;
use App\Models\StudentViolation;
use App\Traits\DateFilterTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
  use DateFilterTrait;

  public function generateViolatorsReport()
  {
    // Check if user is authenticated
    if (!Auth::check()) {
      return redirect()->route('login');
    }

    // Define departments
    $departments = ['SASTE', 'SBAHM', 'SITE', 'SNAHS'];
    $departmentsData = [];
    $totals = [
      'total_cases' => 0,
      'closed_cases' => 0,
      'pending_cases' => 0,
      'unique_violators' => 0,
      'total_population' => 0,
    ];

    // Calculate data for each department
    foreach ($departments as $dept) {
      // Total cases (all violations in department)
      $totalCases = StudentViolation::where('department', $dept)->count();

      // Closed cases (status = 2)
      $closedCases = StudentViolation::where('department', $dept)->where('status', 2)->count();

      // Pending cases (status != 2)
      $pendingCases = StudentViolation::where('department', $dept)->where('status', '!=', 2)->count();

      // Unique violators (distinct student_ids with violations)
      $uniqueViolators = StudentViolation::where('department', $dept)->distinct('student_id')->count();

      // Total population (all students in department)
      $totalPopulation = RoleAccount::where('department', $dept)
        ->whereIn('account_type', ['student', 'alumni'])
        ->count();

      // Calculate percentage
      $percentage = $totalPopulation > 0 ? round(($uniqueViolators / $totalPopulation) * 100, 2) : 0;

      $departmentsData[$dept] = [
        'total_cases' => $totalCases,
        'closed_cases' => $closedCases,
        'pending_cases' => $pendingCases,
        'unique_violators' => $uniqueViolators,
        'total_population' => $totalPopulation,
        'percentage' => $percentage,
      ];

      // Add to totals
      $totals['total_cases'] += $totalCases;
      $totals['closed_cases'] += $closedCases;
      $totals['pending_cases'] += $pendingCases;
      $totals['unique_violators'] += $uniqueViolators;
      $totals['total_population'] += $totalPopulation;
    }

    // Calculate overall percentage
    $totals['percentage'] = $totals['total_population'] > 0
      ? round(($totals['unique_violators'] / $totals['total_population']) * 100, 2)
      : 0;

    // Summary statistics
    $summary = [
      'total_cases' => $totals['total_cases'],
      'total_closed' => $totals['closed_cases'],
      'total_pending' => $totals['pending_cases'],
      'total_violators' => $totals['unique_violators'],
    ];

    // Prepare report data
    $reportData = [
      'generated_date' => now()->format('F j, Y'),
      'generated_time' => now()->format('g:i A'),
      'generated_by' => Auth::user()->fullname,
      'academic_year' => now()->format('Y') . '-' . (now()->format('Y') + 1),
      'departments_data' => $departmentsData,
      'totals' => $totals,
      'summary' => $summary,
    ];

    // Generate PDF
    $pdf = Pdf::loadView('pdf.violators_report', $reportData);
    $pdf->setPaper('letter', 'portrait');

    $filename = 'violators_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';

    return $pdf->download($filename);
  }

  /**
   * Show reports generation page
   */
  public function reportsPage()
  {
    $academicYears = AcademicYear::getActiveYears();
    return view('admin.reports', compact('academicYears'));
  }

  /**
   * Show reports history page
   */
  public function reportsHistory(Request $request)
  {
    $query = GeneratedReport::query();

    // Apply filters
    if ($request->filled('report_type')) {
      $query->where('report_type', $request->report_type);
    }

    if ($request->filled('academic_year')) {
      $query->where('academic_year', $request->academic_year);
    }

    if ($request->filled('generated_by')) {
      $query->where('generated_by', 'like', '%' . $request->generated_by . '%');
    }

    // Get reports with pagination
    $reports = $query->orderByDesc('generated_at')->paginate(20);

    // Get statistics
    $statistics = GeneratedReport::getStatistics();

    // Get academic years for filter
    $academicYears = AcademicYear::getActiveYears();

    return view('admin.reports-history', compact('reports', 'statistics', 'academicYears'));
  }

  /**
   * Generate selected report based on type and academic year
   */
  public function generateSelectedReport(GenerateReportRequest $request)
  {

    $academicYear = $request->academic_year;
    $reportType = $request->report_type;
    $timePeriod = $request->time_period ?? 'all';

    // Parse academic year (e.g., "2024-2025" -> start: 2024, end: 2025)
    $yearParts = explode('-', $academicYear);
    $startYear = $yearParts[0];
    $endYear = $yearParts[1];

    // Create date range for academic year (August to July)
    $startDate = $startYear . '-08-01';
    $endDate = $endYear . '-07-31';

    switch ($reportType) {
      case 'good_moral_applicants':
        return $this->generateGoodMoralApplicantsReport($academicYear, $startDate, $endDate, $timePeriod);

      case 'residency_applicants':
        return $this->generateResidencyApplicantsReport($academicYear, $startDate, $endDate, $timePeriod);

      case 'minor_violators':
        return $this->generateMinorViolatorsReport($academicYear, $startDate, $endDate, $timePeriod);

      case 'major_violators':
        return $this->generateMajorViolatorsReport($academicYear, $startDate, $endDate, $timePeriod);

      case 'overall_report':
        return $this->generateOverallReport($academicYear, $startDate, $endDate, $timePeriod);

      case 'minor_offenses_overall':
        return $this->generateMinorOffensesOverallReport($academicYear, $startDate, $endDate, $timePeriod);

      default:
        return redirect()->back()->with('error', 'Invalid report type selected.');
    }
  }

  /**
   * Generate Good Moral Applicants Report
   */
  private function generateGoodMoralApplicantsReport($academicYear, $startDate, $endDate, $timePeriod = 'all')
  {
    $query = GoodMoralApplication::where('application_status', 'Ready for Pickup'); // Only include completed applications

    // Apply time period filtering if not 'all'
    if ($timePeriod !== 'all') {
      $query = $this->applyDateFilter($query, $timePeriod);
    } else {
      // Use academic year dates if time period is 'all'
      $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    $applications = $query->orderBy('department')
      ->orderBy('created_at', 'desc')
      ->get();

    // Process applications to ensure proper data formatting
    $applications = $applications->map(function ($application) {
      // Ensure reason is properly formatted
      if (is_string($application->reason) && $this->isJson($application->reason)) {
        $application->reason = json_decode($application->reason, true);
      }
      if (is_string($application->reasons_array) && $this->isJson($application->reasons_array)) {
        $application->reasons_array = json_decode($application->reasons_array, true);
      }
      return $application;
    });

    // Get time period description
    $timePeriodInfo = $this->getSpecificTimePeriodDescription($timePeriod);

    $reportData = [
      'generated_date' => now()->format('F j, Y'),
      'generated_time' => now()->format('g:i A'),
      'generated_by' => Auth::user()->fullname,
      'academic_year' => $academicYear,
      'time_period' => $timePeriod,
      'time_period_info' => $timePeriodInfo,
      'report_title' => 'List of Completed Applicants (Certificate of Good Moral Character)',
      'report_subtitle' => $timePeriod !== 'all' ? $timePeriodInfo['description'] : 'A.Y. ' . $academicYear,
      'applications' => $applications,
      'total_count' => $applications->count(),
      'departments_summary' => $applications->groupBy('department')->map->count(),
    ];

    // Create filename with time period info
    $filenameSuffix = $timePeriod !== 'all' ? $timePeriodInfo['filename_suffix'] : $academicYear;
    $filename = 'good_moral_applicants_' . $filenameSuffix . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';

    // Store report information in database
    $this->storeReportRecord([
        'report_type' => 'good_moral_applicants',
        'report_title' => 'List of Completed Applicants (Certificate of Good Moral Character)',
        'academic_year' => $academicYear,
        'time_period' => $timePeriod,
        'time_period_description' => $timePeriod !== 'all' ? $timePeriodInfo['description'] : null,
        'filename' => $filename,
        'total_records' => $applications->count(),
        'summary_data' => [
            'departments_summary' => $applications->groupBy('department')->map(function($group) { return $group->count(); })->toArray(),
            'total_count' => $applications->count(),
        ],
    ]);

    // Generate PDF with header and footer on every page
    return $this->generatePDFWithHeaderFooter('good_moral_applicants_report', $reportData, $filename);
  }

  /**
   * Generate Residency Applicants Report
   */
  private function generateResidencyApplicantsReport($academicYear, $startDate, $endDate, $timePeriod = 'all')
  {
    $query = GoodMoralApplication::where('certificate_type', 'residency')
      ->where('application_status', 'Ready for Pickup'); // Only include completed applications

    // Apply time period filtering if not 'all'
    if ($timePeriod !== 'all') {
      $dateRange = $this->getDateRange($timePeriod);
      $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
    } else {
      // Use academic year dates if time period is 'all'
      $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    $applications = $query->orderBy('department')
      ->orderBy('created_at', 'desc')
      ->get();

    // Process applications to ensure proper data formatting
    $applications = $applications->map(function ($application) {
      // Ensure reason is properly formatted
      if (is_string($application->reason) && $this->isJson($application->reason)) {
        $application->reason = json_decode($application->reason, true);
      }
      if (is_string($application->reasons_array) && $this->isJson($application->reasons_array)) {
        $application->reasons_array = json_decode($application->reasons_array, true);
      }
      return $application;
    });

    // Get time period description
    $timePeriodInfo = $this->getSpecificTimePeriodDescription($timePeriod);

    $reportData = [
      'generated_date' => now()->format('F j, Y'),
      'generated_time' => now()->format('g:i A'),
      'generated_by' => Auth::user()->fullname,
      'academic_year' => $academicYear,
      'time_period' => $timePeriod,
      'time_period_info' => $timePeriodInfo,
      'report_title' => 'List of Completed Applicants (Certificate of Residency)',
      'report_subtitle' => $timePeriod !== 'all' ? $timePeriodInfo['description'] : 'A.Y. ' . $academicYear,
      'applications' => $applications,
      'total_count' => $applications->count(),
      'departments_summary' => $applications->groupBy('department')->map->count(),
    ];

    // Try wkhtmltopdf first, fallback to DomPDF if not available
    try {
        // Generate PDF using wkhtmltopdf with header and footer
        // Create temporary files for header and footer
        $headerPath = storage_path('app/temp_header_' . uniqid() . '.html');
        $footerPath = storage_path('app/temp_footer_' . uniqid() . '.html');
        
        file_put_contents($headerPath, view('pdf.wkhtmltopdf.header')->render());
        file_put_contents($footerPath, view('pdf.wkhtmltopdf.footer')->render());

        $pdf = SnappyPdf::loadView('pdf.wkhtmltopdf.residency_applicants_report', $reportData)
            ->setOption('page-size', 'A4')
            ->setOption('orientation', 'Portrait')
            ->setOption('margin-top', '30mm')
            ->setOption('margin-bottom', '25mm')
            ->setOption('margin-left', '15mm')
            ->setOption('margin-right', '15mm')
            ->setOption('header-html', $headerPath)
            ->setOption('header-spacing', '3')
            ->setOption('footer-html', $footerPath)
            ->setOption('footer-spacing', '3')
            ->setOption('enable-local-file-access', true);

        // Create filename with time period info
        $filenameSuffix = $timePeriod !== 'all' ? $timePeriodInfo['filename_suffix'] : $academicYear;
        $filename = 'residency_applicants_' . $filenameSuffix . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        return $pdf->download($filename);

    } catch (\Exception $e) {
        // Fallback to DomPDF if wkhtmltopdf fails
        Log::warning('wkhtmltopdf failed, falling back to DomPDF: ' . $e->getMessage());

        $pdf = Pdf::loadView('pdf.residency_applicants_report', $reportData);
        $pdf->setPaper('letter', 'portrait');

        // Create filename with time period info
        $filenameSuffix = $timePeriod !== 'all' ? $timePeriodInfo['filename_suffix'] : $academicYear;
        $filename = 'residency_applicants_' . $filenameSuffix . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        // Generate PDF
        $pdfOutput = $pdf->download($filename);
        
        // Clean up temporary files
        @unlink($headerPath);
        @unlink($footerPath);
        
        return $pdfOutput;

    } catch (\Exception $e) {
        // Clean up temporary files if they exist
        if (isset($headerPath)) @unlink($headerPath);
        if (isset($footerPath)) @unlink($footerPath);
        
        // Fallback to DomPDF if wkhtmltopdf fails
        Log::warning('wkhtmltopdf failed for residency report, falling back to DomPDF: ' . $e->getMessage());

        $pdf = Pdf::loadView('pdf.residency_applicants_report', $reportData);
        $pdf->setPaper('letter', 'portrait');

        $filenameSuffix = $timePeriod !== 'all' ? $timePeriodInfo['filename_suffix'] : $academicYear;
        $filename = 'residency_applicants_' . $filenameSuffix . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        return $pdf->download($filename);
    }
  }

  /**
   * Generate Minor Violators Report
   */
  private function generateMinorViolatorsReport($academicYear, $startDate, $endDate, $timePeriod = 'all')
  {
    // Include student relationship for year level information
    $query = StudentViolation::with('studentAccount')->where('offense_type', 'minor');

    // Apply time period filtering if not 'all'
    if ($timePeriod !== 'all') {
      $dateRange = $this->getDateRange($timePeriod);
      $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
    } else {
      // Use academic year dates if time period is 'all'
      $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    $violations = $query->orderBy('department')
      ->orderBy('created_at', 'desc')
      ->get();

    // Get time period description
    $timePeriodInfo = $this->getSpecificTimePeriodDescription($timePeriod);

    $reportData = [
      'generated_date' => now()->format('F j, Y'),
      'generated_time' => now()->format('g:i A'),
      'generated_by' => Auth::user()->fullname,
      'academic_year' => $academicYear,
      'time_period' => $timePeriod,
      'time_period_info' => $timePeriodInfo,
      'report_title' => 'List of Violators (Minor Offenses)',
      'report_subtitle' => $timePeriod !== 'all' ? $timePeriodInfo['description'] : 'A.Y. ' . $academicYear,
      'violations' => $violations,
      'total_count' => $violations->count(),
      'departments_summary' => $violations->groupBy('department')->map->count(),
      'unique_violators' => $violations->unique('student_id')->count(),
    ];

    $pdf = Pdf::loadView('pdf.minor_violators_report', $reportData);
    $pdf->setPaper('letter', 'portrait');

    // Create filename with time period info
    $filenameSuffix = $timePeriod !== 'all' ? $timePeriodInfo['filename_suffix'] : $academicYear;
    $filename = 'minor_violators_' . $filenameSuffix . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
    return $pdf->download($filename);
  }

  /**
   * Generate Major Violators Report
   */
  private function generateMajorViolatorsReport($academicYear, $startDate, $endDate, $timePeriod = 'all')
  {
    // Include student relationship for year level information
    $query = StudentViolation::with('studentAccount')->where('offense_type', 'major');

    // Apply time period filtering if not 'all'
    if ($timePeriod !== 'all') {
      $dateRange = $this->getDateRange($timePeriod);
      $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
    } else {
      // Use academic year dates if time period is 'all'
      $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    $violations = $query->orderBy('department')
      ->orderBy('created_at', 'desc')
      ->get();

    // Get time period description
    $timePeriodInfo = $this->getSpecificTimePeriodDescription($timePeriod);

    $reportData = [
      'generated_date' => now()->format('F j, Y'),
      'generated_time' => now()->format('g:i A'),
      'generated_by' => Auth::user()->fullname,
      'academic_year' => $academicYear,
      'time_period' => $timePeriod,
      'time_period_info' => $timePeriodInfo,
      'report_title' => 'List of Violators (Major Offenses)',
      'report_subtitle' => $timePeriod !== 'all' ? $timePeriodInfo['description'] : 'A.Y. ' . $academicYear,
      'violations' => $violations,
      'total_count' => $violations->count(),
      'departments_summary' => $violations->groupBy('department')->map->count(),
      'unique_violators' => $violations->unique('student_id')->count(),
    ];

    $pdf = Pdf::loadView('pdf.major_violators_report', $reportData);
    $pdf->setPaper('letter', 'portrait');

    // Create filename with time period info
    $filenameSuffix = $timePeriod !== 'all' ? $timePeriodInfo['filename_suffix'] : $academicYear;
    $filename = 'major_violators_' . $filenameSuffix . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
    return $pdf->download($filename);
  }

  /**
   * Generate Minor Offenses Overall Report
   */
  private function generateMinorOffensesOverallReport($academicYear, $startDate, $endDate, $timePeriod = 'all')
  {
    // Data showing progression from A.Y. 2023-2024 to June 2025 for minor offenses
    $departmentsData = [
      'SITE' => [
        'total_population' => 640,
        'violators_2023_2024' => 118,
        'violators_june_2025' => 46, // Combined February (23) + June (23) data as of June 2025
      ],
      'SBAHM' => [
        'total_population' => 727,
        'violators_2023_2024' => 88,
        'violators_june_2025' => 42, // Combined February (21) + June (21) data as of June 2025
      ],
      'SNAHS' => [
        'total_population' => 2831,
        'violators_2023_2024' => 524,
        'violators_june_2025' => 154, // Combined February (77) + June (77) data as of June 2025
      ],
      'SASTE' => [
        'total_population' => 409,
        'violators_2023_2024' => 97,
        'violators_june_2025' => 24, // Combined February (12) + June (12) data as of June 2025
      ]
    ];

    $trendsData = [];
    $totals = [
      'total_population' => 0,
      'violators_2023_2024' => 0,
      'violators_june_2025' => 0,
      'total_variance' => 0,
    ];

    foreach ($departmentsData as $dept => $data) {
      // Calculate variance using the formula: (previous - current / previous) × 100
      $previousViolators = $data['violators_2023_2024'];
      $juneViolators = $data['violators_june_2025'];

      // Calculate variance percentage from 2023-2024 to June 2025
      $variancePercentage = $previousViolators > 0
        ? round((($previousViolators - $juneViolators) / $previousViolators) * 100, 1)
        : 0;

      // Calculate raw difference for display (June 2025 vs 2023-2024)
      $rawDifference = $juneViolators - $previousViolators;

      $trendsData[$dept] = [
        'department' => $dept,
        'total_population' => $data['total_population'],
        'violators_2023_2024' => $data['violators_2023_2024'],
        'violators_june_2025' => $data['violators_june_2025'],
        'variance' => $rawDifference,
        'variance_percentage' => $variancePercentage,
        'trend' => $rawDifference > 0 ? 'increase' : ($rawDifference < 0 ? 'decrease' : 'stable')
      ];

      // Add to totals
      $totals['total_population'] += $data['total_population'];
      $totals['violators_2023_2024'] += $data['violators_2023_2024'];
      $totals['violators_june_2025'] += $data['violators_june_2025'];
      $totals['total_variance'] += $rawDifference;
    }

    $reportData = [
      'generated_date' => now()->format('F j, Y'),
      'generated_time' => now()->format('g:i A'),
      'generated_by' => Auth::user()->fullname,
      'academic_year' => $academicYear,
      'report_title' => 'Overall Report on Minor Offenses as of June 2025',
      'departments_data' => $trendsData,
      'totals' => $totals,
    ];

    $pdf = Pdf::loadView('pdf.minor_offenses_overall_report', $reportData);
    $pdf->setPaper('letter', 'portrait');

    $filename = 'minor_offenses_overall_' . $academicYear . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
    return $pdf->download($filename);
  }

  /**
   * Helper method to check if a string is valid JSON
   */
  private function isJson($string)
  {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
  }

  /**
   * Generate Overall Report
   */
  private function generateOverallReport($academicYear, $startDate, $endDate, $timePeriod = 'all')
  {
    // Define departments
    $departments = ['SASTE', 'SBAHM', 'SITE', 'SNAHS', 'SOM', 'GRADSCH'];
    $departmentsData = [];
    $totals = [
      'total_cases' => 0,
      'closed_cases' => 0,
      'pending_cases' => 0,
      'unique_violators' => 0,
      'total_population' => 0,
    ];

    // Calculate data for each department within the academic year
    foreach ($departments as $dept) {
      // Total cases (all violations in department within academic year)
      $totalCases = StudentViolation::where('department', $dept)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

      // Closed cases (status = 2)
      $closedCases = StudentViolation::where('department', $dept)
        ->where('status', 2)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

      // Pending cases (status != 2)
      $pendingCases = StudentViolation::where('department', $dept)
        ->where('status', '!=', 2)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

      // Unique violators (distinct student_ids with violations in academic year)
      $uniqueViolators = StudentViolation::where('department', $dept)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->distinct('student_id')
        ->count();

      // Total population (all students in department)
      $totalPopulation = RoleAccount::where('department', $dept)
        ->whereIn('account_type', ['student', 'alumni'])
        ->count();

      // Calculate percentage
      $percentage = $totalPopulation > 0 ? round(($uniqueViolators / $totalPopulation) * 100, 2) : 0;

      $departmentsData[$dept] = [
        'total_cases' => $totalCases,
        'closed_cases' => $closedCases,
        'pending_cases' => $pendingCases,
        'unique_violators' => $uniqueViolators,
        'total_population' => $totalPopulation,
        'percentage' => $percentage,
      ];

      // Add to totals
      $totals['total_cases'] += $totalCases;
      $totals['closed_cases'] += $closedCases;
      $totals['pending_cases'] += $pendingCases;
      $totals['unique_violators'] += $uniqueViolators;
      $totals['total_population'] += $totalPopulation;
    }

    // Calculate overall percentage
    $totals['percentage'] = $totals['total_population'] > 0
      ? round(($totals['unique_violators'] / $totals['total_population']) * 100, 2)
      : 0;

    // Summary statistics
    $summary = [
      'total_cases' => $totals['total_cases'],
      'total_closed' => $totals['closed_cases'],
      'total_pending' => $totals['pending_cases'],
      'total_violators' => $totals['unique_violators'],
    ];

    // Get time period description
    $timePeriodInfo = $this->getSpecificTimePeriodDescription($timePeriod);

    $reportData = [
      'generated_date' => now()->format('F j, Y'),
      'generated_time' => now()->format('g:i A'),
      'generated_by' => Auth::user()->fullname,
      'academic_year' => $academicYear,
      'time_period' => $timePeriod,
      'time_period_info' => $timePeriodInfo,
      'report_subtitle' => $timePeriod !== 'all' ? $timePeriodInfo['description'] : 'A.Y. ' . $academicYear,
      'departments_data' => $departmentsData,
      'totals' => $totals,
      'summary' => $summary,
    ];

    $pdf = Pdf::loadView('pdf.violators_report', $reportData);
    $pdf->setPaper('letter', 'portrait');

    // Create filename with time period info
    $filenameSuffix = $timePeriod !== 'all' ? $timePeriodInfo['filename_suffix'] : $academicYear;
    $filename = 'overall_report_' . $filenameSuffix . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
    return $pdf->download($filename);
  }

  private function storeReportRecord($data)
  {
    try {
      GeneratedReport::createReportRecord([
        'report_type' => $data['report_type'],
        'report_title' => $data['report_title'],
        'academic_year' => $data['academic_year'],
        'time_period' => $data['time_period'],
        'time_period_description' => $data['time_period_description'] ?? null,
        'filename' => $data['filename'],
        'total_records' => $data['total_records'] ?? 0,
        'summary_data' => $data['summary_data'] ?? null,
        'generated_by' => Auth::user()->fullname ?? Auth::user()->email ?? 'System',
        'generated_by_role' => Auth::user()->account_type ?? 'Unknown',
        'generated_at' => now(),
        'status' => 'completed',
      ]);
    } catch (\Exception $e) {
      Log::warning('Failed to store report record: ' . $e->getMessage());
    }
  }

  private function generatePDFWithHeaderFooter($viewName, $reportData, $filename)
  {
    try {
        // Create temporary files for header and footer
        $headerPath = storage_path('app/temp_header_' . uniqid() . '.html');
        $footerPath = storage_path('app/temp_footer_' . uniqid() . '.html');
        
        file_put_contents($headerPath, view('pdf.wkhtmltopdf.header')->render());
        file_put_contents($footerPath, view('pdf.wkhtmltopdf.footer')->render());

        $pdf = SnappyPdf::loadView('pdf.wkhtmltopdf.' . $viewName, $reportData)
            ->setOption('page-size', 'A4')
            ->setOption('orientation', 'Portrait')
            ->setOption('margin-top', '40mm')
            ->setOption('margin-bottom', '30mm')
            ->setOption('margin-left', '15mm')
            ->setOption('margin-right', '15mm')
            ->setOption('header-html', $headerPath)
            ->setOption('header-spacing', '5')
            ->setOption('footer-html', $footerPath)
            ->setOption('footer-spacing', '3')
            ->setOption('footer-line', false)
            ->setOption('header-line', false)
            ->setOption('enable-local-file-access', true)
            ->setOption('print-media-type', true)
            ->setOption('no-stop-slow-scripts', true);

        // Generate PDF
        $pdfOutput = $pdf->download($filename);
        
        // Clean up temporary files
        @unlink($headerPath);
        @unlink($footerPath);
        
        return $pdfOutput;

    } catch (\Exception $e) {
        // Clean up temporary files if they exist
        if (isset($headerPath)) @unlink($headerPath);
        if (isset($footerPath)) @unlink($footerPath);
        
        // Fallback to DomPDF if wkhtmltopdf fails
        Log::warning('wkhtmltopdf failed, falling back to DomPDF: ' . $e->getMessage());

        $pdf = Pdf::loadView('pdf.' . $viewName, $reportData);
        $pdf->setPaper('letter', 'portrait');
        return $pdf->download($filename);
    }
  }
}
