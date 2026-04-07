<?php

namespace App\Http\Controllers\GoodMoral;
use App\Http\Controllers\Controller;

use App\Services\ReceiptService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\ViolationNotif;
use App\Models\GoodMoralApplication;
use App\Models\StudentViolation;
use App\Models\RoleAccount;
use App\Models\StudentRegistration;
use App\Models\Violation;
use App\Models\ArchivedRoleAccount;
use App\Models\HeadOSAApplication;
use App\Models\DeanApplication;
use App\Traits\RoleCheck;
use App\Traits\DateFilterTrait;
use App\Traits\ViolationEscalationTrait;
use App\Models\NotifArchive;
use App\Models\SecOSAApplication;
use App\Models\AcademicYear;
use App\Models\GeneratedReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;

class AdminController extends Controller
{
  use RoleCheck, DateFilterTrait, ViolationEscalationTrait;

  public function __construct()
  {
    // Temporarily disable role check to fix authentication
    // $this->checkRole(['admin']);
  }
  public function dashboard(Request $request)
  {
    // Check if user is authenticated
    if (!auth()->check()) {
      return redirect()->route('login');
    }

    // User authentication already verified by login system

    // Get frequency filter from request
    $frequency = $request->get('frequency', 'all');

    // Applicants per department with date filtering
    $site = $this->applyDateFilter(GoodMoralApplication::where('department', 'SITE'), $frequency)->count();
    $saste = $this->applyDateFilter(GoodMoralApplication::where('department', 'SASTE'), $frequency)->count();
    $sbahm = $this->applyDateFilter(GoodMoralApplication::where('department', 'SBAHM'), $frequency)->count();
    $snahs = $this->applyDateFilter(GoodMoralApplication::where('department', 'SNAHS'), $frequency)->count();
    $som = $this->applyDateFilter(GoodMoralApplication::where('department', 'SOM'), $frequency)->count();
    $gradsch = $this->applyDateFilter(GoodMoralApplication::where('department', 'GRADSCH'), $frequency)->count();

    // For Pie Chart stats with date filtering
    $minorpending = $this->applyDateFilter(StudentViolation::where('status', '!=', 2)->where('offense_type', 'minor'), $frequency)->count();
    $minorcomplied = $this->applyDateFilter(StudentViolation::where('status', '=', 2)->where('offense_type', 'minor'), $frequency)->count();
    $majorpending = $this->applyDateFilter(StudentViolation::where('status', '!=', 2)->where('offense_type', 'major'), $frequency)->count();
    $majorcomplied = $this->applyDateFilter(StudentViolation::where('status', '=', 2)->where('offense_type', 'major'), $frequency)->count();

    // Percentages for minor offenses
    $totalMinor = $minorpending + $minorcomplied;
    $pendingPercent = $totalMinor > 0 ? ($minorpending / $totalMinor) * 100 : 0;
    $compliedPercent = 100 - $pendingPercent;
    $dashArray = $pendingPercent . ' ' . $compliedPercent;

    // ✅ Percentages for major offenses
    $totalMajor = $majorpending + $majorcomplied;
    $majorPendingPercent = $totalMajor > 0 ? ($majorpending / $totalMajor) * 100 : 0;
    $majorCompliedPercent = 100 - $majorPendingPercent;
    $majorDashArray = $majorPendingPercent . ' ' . $majorCompliedPercent;

    // Departments array for looping
    $departments = ['SITE', 'SASTE', 'SBAHM', 'SNAHS', 'SOM', 'GRADSCH'];

    // Prepare arrays for counts
    $majorCounts = [];
    $minorCounts = [];
    $majorViolationsByDept = [];
    $minorViolationsByDept = [];

    foreach ($departments as $dept) {
      $majorCounts[$dept] = $this->applyDateFilter(StudentViolation::where('offense_type', 'major')
        ->where('department', $dept), $frequency)
        ->count();

      $minorCounts[$dept] = $this->applyDateFilter(StudentViolation::where('offense_type', 'minor')
        ->where('department', $dept), $frequency)
        ->count();

      // For dashboard charts
      $majorViolationsByDept[$dept] = $majorCounts[$dept];
      $minorViolationsByDept[$dept] = $minorCounts[$dept];
    }

    // Calculate totals and percentages for dashboard
    $minorTotal = $minorpending + $minorcomplied;
    $majorTotal = $majorpending + $majorcomplied;

    $minorResolved = $minorcomplied;
    $minorPending = $minorpending;
    $majorResolved = $majorcomplied;
    $majorPending = $majorpending;

    $minorResolvedPercentage = $minorTotal > 0 ? round(($minorResolved / $minorTotal) * 100) : 0;
    $majorResolvedPercentage = $majorTotal > 0 ? round(($majorResolved / $majorTotal) * 100) : 0;

    // Pagination
    $violationpage = Violation::paginate(10);

    // Get escalation notifications for admin
    $currentAdmin = auth()->user();
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
    $admin = auth()->user();

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
   * Get notification counts for admin sidebar
   */
  public function getNotificationCounts()
  {
    // Count pending Good Moral applications that need admin approval (approved by dean)
    $pendingApplications = GoodMoralApplication::where('application_status', 'LIKE', 'Approved by Dean:%')
      ->whereNotIn('application_status', ['Approved by Administrator', 'Rejected by Administrator'])
      ->count();

    // Count pending PSG applications
    $psgApplications = RoleAccount::where('account_type', 'psg_officer')
      ->where('status', '5') // Pending status
      ->count();

    // Count pending violations that need admin attention
    $pendingViolations = StudentViolation::where('status', 0) // Pending status
      ->count();

    // Count students with 3+ minor violations (escalation notifications)
    $escalationNotifications = StudentViolation::select('student_id')
      ->where('offense_type', 'minor')
      ->groupBy('student_id')
      ->havingRaw('COUNT(*) >= 3')
      ->count();

    return response()->json([
      'pendingApplications' => $pendingApplications,
      'psgApplications' => $psgApplications,
      'pendingViolations' => $pendingViolations,
      'escalationNotifications' => $escalationNotifications,
    ]);
  }

  /**
   * Show escalation notifications for students with 3 minor violations
   */
  public function escalationNotifications()
  {
    // Get all students who have 3 or more minor violations
    $escalatedStudents = StudentViolation::select('student_id', 'first_name', 'last_name', 'department')
      ->selectRaw('COUNT(*) as minor_violation_count')
      ->where('offense_type', 'minor')
      ->groupBy('student_id', 'first_name', 'last_name', 'department')
      ->havingRaw('COUNT(*) >= 3')
      ->orderBy('minor_violation_count', 'desc')
      ->get();

    // For each student, get their violation details and check if major violation was created
    $escalationNotifications = [];
    foreach ($escalatedStudents as $student) {
      // Get all minor violations for this student
      $minorViolations = StudentViolation::where('student_id', $student->student_id)
        ->where('offense_type', 'minor')
        ->orderBy('created_at', 'desc')
        ->get();

      // Check if a major violation was automatically created for this student
      $autoMajorViolation = StudentViolation::where('student_id', $student->student_id)
        ->where('offense_type', 'major')
        ->where('violation', 'LIKE', '%Escalated from 3 minor violations%')
        ->first();

      $escalationNotifications[] = [
        'student_id' => $student->student_id,
        'fullname' => $student->first_name . ' ' . $student->last_name,
        'department' => $student->department,
        'course' => null, // Course column doesn't exist in student_violations table
        'minor_violation_count' => $student->minor_violation_count,
        'minor_violations' => $minorViolations,
        'auto_major_violation' => $autoMajorViolation,
        'escalation_status' => $autoMajorViolation ? 'escalated' : 'pending_escalation',
        'latest_violation_date' => $minorViolations->first()->created_at ?? null,
      ];
    }

    // Sort by latest violation date (most recent first)
    usort($escalationNotifications, function($a, $b) {
      if (!$a['latest_violation_date'] || !$b['latest_violation_date']) {
        return 0;
      }
      return $b['latest_violation_date']->timestamp - $a['latest_violation_date']->timestamp;
    });

    return view('admin.escalationNotifications', compact('escalationNotifications'));
  }

  /**
   * Manually trigger escalation for a student with 3+ minor violations
   */
  public function triggerManualEscalation($student_id)
  {
    try {
      // Get student information from their violations
      $studentInfo = StudentViolation::where('student_id', $student_id)
        ->where('offense_type', 'minor')
        ->first();

      if (!$studentInfo) {
        return response()->json([
          'success' => false,
          'message' => 'Student not found or has no minor violations.'
        ]);
      }

      // Count minor violations
      $minorViolationCount = StudentViolation::where('student_id', $student_id)
        ->where('offense_type', 'minor')
        ->count();

      if ($minorViolationCount < 3) {
        return response()->json([
          'success' => false,
          'message' => 'Student does not have 3 or more minor violations.'
        ]);
      }

      // Check if major violation already exists
      $existingMajorViolation = StudentViolation::where('student_id', $student_id)
        ->where('offense_type', 'major')
        ->where('violation', 'LIKE', '%Automatic escalation%')
        ->first();

      if ($existingMajorViolation) {
        return response()->json([
          'success' => false,
          'message' => 'Major violation already exists for this student.'
        ]);
      }

      // Create automatic major violation
      $admin = auth()->user();
      $referenceNumber = 'MAJ-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

      $majorViolation = StudentViolation::create([
        'student_id' => $student_id,
        'first_name' => $studentInfo->first_name,
        'last_name' => $studentInfo->last_name,
        'department' => $studentInfo->department,
        'course' => $studentInfo->course,
        'violation' => 'Automatic escalation: Accumulated 3 minor violations (Manual trigger by Admin)',
        'offense_type' => 'major',
        'ref_num' => $referenceNumber,
        'status' => 0, // Pending
        'added_by' => 'Admin: ' . $admin->fullname,
        'unique_id' => uniqid(),
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Major violation created successfully.',
        'violation_id' => $majorViolation->id,
        'reference_number' => $referenceNumber
      ]);

    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage()
      ]);
    }
  }

  public function create(Request $request)
  {
    $validated = $request->validate([
      'offense_type' => ['required', 'in:minor,major'],
      'description' => ['required', 'string', 'max:255'],
      'article' => ['nullable', 'string', 'max:100'],
    ]);

    Violation::create([
      'offense_type' => $validated['offense_type'],
      'description' => $validated['description'],
      'article' => $validated['article'],
    ]);
    $violations = Violation::paginate(10);
    return redirect()->back()->with('success', 'Violation successfully recorded.');
  }
  public function AddViolationDashboard()
  {

    $violations = Violation::get();
    $violationpage = Violation::paginate(10);
    return view('admin.AddViolation', compact('violations', 'violationpage'));
  }

  public function AddViolatorDashboard()
  {
    $violations = Violation::get();

    $coursesByDepartment = [
      'SITE' => ['BSIT', 'BLIS', 'BS ENSE', 'BS CpE', 'BSCE'],
      'SBAHM' => ['BSA', 'BSE', 'BSBAMM', 'BSBA MFM', 'BSBA MOP', 'BSMA', 'BSHM', 'BSTM', 'BSPDMI'],
      'SASTE' => ['BAELS', 'BS Psych', 'BS Bio', 'BSSW', 'BSPA', 'BS Bio MB', 'BSEd', 'BEEd', 'BPEd'],
      'SNAHS' => ['BSN', 'BSPh', 'BSMT', 'BSPT', 'BSRT'],
      'SOM' => ['MD', 'BS Med'],
      'GRADSCH' => ['MBA', 'MPA', 'MEd', 'MS', 'MA', 'PhD', 'EdD'],
    ];

    return view('admin.AddViolator', compact('violations', 'coursesByDepartment'));
  }

  public function storeViolator(Request $request)
  {
    try {
      // Check if this is a multiple students or multiple violations submission
      $multipleStudentsData = $request->input('multiple_students_data');
      $multipleViolationsData = $request->input('multiple_violations_data');

      if ($multipleStudentsData || $multipleViolationsData) {
        return $this->addMultipleViolators($request, $multipleStudentsData, $multipleViolationsData);
      }
    } catch (\Exception $e) {
      return redirect()->route('admin.AddViolator')->with('error', 'An error occurred: ' . $e->getMessage());
    }

    // Single student validation
    $validated = $request->validate([
      'first_name' => ['required', 'string', 'max:255'],
      'last_name' => ['required', 'string', 'max:255'],
      'student_id' => ['required', 'string', 'max:255'],
      'department' => ['required', 'string', 'max:255'],
      'course' => ['required', 'string', 'max:255'],
      'offense_type' => ['required', 'in:minor,major'],
      'violation' => ['required', 'string'],
      'ref_num' => ['nullable', 'string', 'max:255'],
    ]);

    // Generate unique ID
    $uniqueID = uniqid();

    // Get current user name
    $userName = auth()->user()->fullname ?? 'Admin';

    $violation = StudentViolation::create([
      'first_name' => $validated['first_name'],
      'last_name' => $validated['last_name'],
      'student_id' => $validated['student_id'],
      'department' => $validated['department'],
      'course' => $validated['course'],
      'offense_type' => $validated['offense_type'],
      'violation' => $validated['violation'],
      'ref_num' => $validated['ref_num'],
      'added_by' => $userName,
      'status' => '0', // Pending status
      'unique_id' => $uniqueID,
    ]);

    // Get the violation details to find article reference
    $violationRecord = Violation::where('description', $violation->violation)->first();
    $article = $violationRecord ? $violationRecord->article : null;

    // Create notification for the student about the violation using new format
    ViolationNotif::create([
      'ref_num' => $violation->ref_num ?? 'VIOLATION-' . $violation->id,
      'student_id' => $violation->student_id,
      'status' => 0, // Under review
      'notif' => generateViolationNotification($violation->offense_type, $violation->violation, $article, $violation->added_by),
    ]);

    $successMessage = 'Violator added successfully!';

    // Check for escalation if this is a minor violation
    if ($violation->offense_type === 'minor') {
      $escalated = $this->checkMinorViolationEscalation($validated['student_id']);
      if ($escalated) {
        $successMessage = 'Violator added successfully! 🚨 AUTOMATIC ESCALATION: This student now has 3 minor violations. A MAJOR VIOLATION has been automatically created and all admins have been notified.';
      }
    }

    return redirect()->route('admin.AddViolator')->with('success', $successMessage);
  }

  /**
   * Show the multiple violators form
   */
  public function addMultipleViolatorsForm()
  {
    $violations = Violation::all();
    return view('admin.AddMultipleViolators', compact('violations'));
  }

  /**
   * Store multiple violators
   */
  public function storeMultipleViolators(Request $request)
  {
    // Prevent duplicate submissions within 10 seconds
    $cacheKey = 'multiple_violators_' . auth()->id() . '_' . md5(json_encode($request->all()));
    if (cache()->has($cacheKey)) {
      \Log::warning('Duplicate submission prevented:', [
        'user_id' => auth()->id(),
        'cache_key' => $cacheKey,
        'timestamp' => now()->toDateTimeString()
      ]);
      return redirect()->route('admin.AddMultipleViolators')->with('warning', 'Duplicate submission prevented. Please wait before submitting again.');
    }

    // Set cache to prevent duplicates for 10 seconds
    cache()->put($cacheKey, true, 10);

    // Enhanced Debug: Log all request data
    \Log::info('🚀 Multiple Violators Request Started:', [
      'timestamp' => now()->toDateTimeString(),
      'user_id' => auth()->id(),
      'user_name' => auth()->user()->fullname ?? 'Unknown',
      'all_data' => $request->all(),
      'offense_type' => $request->input('offense_type'),
      'violation' => $request->input('violation'),
      'multiple_violations_data' => $request->input('multiple_violations_data'),
      'student_ids' => $request->input('student_ids'),
      'ref_num' => $request->input('ref_num'),
      'request_method' => $request->method(),
      'request_url' => $request->url(),
    ]);

    // Add immediate response for debugging
    if ($request->has('debug_mode')) {
      return response()->json([
        'status' => 'debug',
        'message' => 'Multiple violators endpoint reached successfully',
        'data' => $request->all()
      ]);
    }

    // Custom validation logic based on whether it's multiple violations or single violation
    $hasMultipleViolations = !empty($request->input('multiple_violations_data'));

    if ($hasMultipleViolations) {
      // For multiple violations, violation field can be empty
      $validated = $request->validate([
        'offense_type' => ['required', 'in:minor,major'],
        'multiple_violations_data' => ['required', 'string'],
        'student_ids' => ['required', 'array', 'min:1'],
        'student_ids.*' => ['required', 'string'],
        'ref_num' => ['nullable', 'string', 'max:255'],
      ]);
    } else {
      // For single violation, violation field is required
      $validated = $request->validate([
        'offense_type' => ['required', 'in:minor,major'],
        'violation' => ['required', 'string'],
        'student_ids' => ['required', 'array', 'min:1'],
        'student_ids.*' => ['required', 'string'],
        'ref_num' => ['nullable', 'string', 'max:255'],
      ]);
    }

    \Log::info('Validated Data:', $validated);
    \Log::info('Has Multiple Violations:', ['value' => $hasMultipleViolations]);

    // Determine violations to process
    $violationsToProcess = [];

    if ($hasMultipleViolations) {
      // Multiple violations mode
      $multipleViolations = json_decode($validated['multiple_violations_data'], true);
      if (is_array($multipleViolations) && !empty($multipleViolations)) {
        $violationsToProcess = $multipleViolations;
        \Log::info('Processing multiple violations:', $violationsToProcess);
      } else {
        \Log::error('Invalid multiple violations data:', ['data' => $validated['multiple_violations_data']]);
        return redirect()->back()->withErrors(['multiple_violations_data' => 'Invalid violations data provided.']);
      }
    } else {
      // Single violation mode
      if (!empty($validated['violation'])) {
        $violationsToProcess = [['description' => $validated['violation']]];
        \Log::info('Processing single violation:', $violationsToProcess);
      } else {
        return redirect()->back()->withErrors(['violation' => 'Please select a violation.']);
      }
    }

    if (empty($violationsToProcess)) {
      \Log::error('No violations to process');
      return redirect()->back()->withErrors(['violation' => 'Please select at least one violation.']);
    }

    $userName = auth()->user()->fullname ?? 'Admin';
    $createdViolations = [];
    $escalationMessages = [];

    foreach ($validated['student_ids'] as $studentId) {
      // Find student info
      $student = RoleAccount::where('student_id', $studentId)->first();
      if (!$student) continue;

      // Parse name
      $nameParts = explode(' ', $student->fullname);
      $firstName = $nameParts[0] ?? '';
      $lastName = end($nameParts) ?? '';

      // Create violations for this student
      foreach ($violationsToProcess as $violationData) {
        $violation = StudentViolation::create([
          'first_name' => $firstName,
          'last_name' => $lastName,
          'student_id' => $studentId,
          'department' => $student->department,
          'course' => $student->course ?? 'N/A',
          'offense_type' => $validated['offense_type'],
          'violation' => $violationData['description'],
          'ref_num' => $validated['ref_num'],
          'added_by' => $userName,
          'status' => '0',
          'unique_id' => uniqid(),
        ]);

        // Create notification
        $violationRecord = Violation::where('description', $violation->violation)->first();
        $article = $violationRecord ? $violationRecord->article : null;

        ViolationNotif::create([
          'ref_num' => $violation->ref_num ?? 'VIOLATION-' . $violation->id,
          'student_id' => $violation->student_id,
          'status' => 0,
          'notif' => generateViolationNotification($violation->offense_type, $violation->violation, $article, $violation->added_by),
        ]);
      }

      // Check for escalation (only once per student)
      if ($validated['offense_type'] === 'minor') {
        $escalated = $this->checkMinorViolationEscalation($studentId);
        if ($escalated) {
          $escalationMessages[] = $student->fullname;
        }
      }
    }

    $studentCount = count($validated['student_ids']);
    $violationCount = count($violationsToProcess);

    if ($violationCount > 1) {
      $successMessage = "Successfully added {$violationCount} violations for {$studentCount} students!";
    } else {
      $successMessage = "Successfully added violation for {$studentCount} students!";
    }

    if (!empty($escalationMessages)) {
      $escalatedNames = implode(', ', $escalationMessages);
      $successMessage .= ' 🚨 AUTOMATIC ESCALATION: The following students now have 3 minor violations: ' . $escalatedNames;
    }

    return redirect()->route('admin.AddMultipleViolators')->with('success', $successMessage);
  }

  /**
   * Add multiple violators and/or multiple violations
   */
  private function addMultipleViolators(Request $request, $multipleStudentsData, $multipleViolationsData)
  {
    try {
      // Determine what type of multiple submission this is
      $hasMultipleStudents = !empty($multipleStudentsData);
      $hasMultipleViolations = !empty($multipleViolationsData);

      if ($hasMultipleViolations) {
        // Validate for multiple violations
        $validated = $request->validate([
          'offense_type' => ['required', 'in:minor,major'],
          'multiple_violations_data' => ['required', 'string'],
          'student_ids' => ['required', 'array'],
          'student_ids.*' => ['required', 'string'],
          'ref_num' => ['nullable', 'string', 'max:255'],
        ]);
      } else {
        // Validate for single violation
        $validated = $request->validate([
          'offense_type' => ['required', 'in:minor,major'],
          'violation' => ['required', 'string'],
          'student_ids' => ['required', 'array'],
          'student_ids.*' => ['required', 'string'],
          'ref_num' => ['nullable', 'string', 'max:255'],
        ]);
      }

      // Handle students data
      $students = [];
      if ($hasMultipleStudents) {
        $students = json_decode($multipleStudentsData, true);
        if (!$students || !is_array($students) || empty($students)) {
          return redirect()->route('admin.AddViolator')->with('error', 'No students data provided.');
        }
      } else {
        // Single student from form fields
        $students = [[
          'student_id' => $request->input('student_id'),
          'first_name' => $request->input('first_name'),
          'last_name' => $request->input('last_name'),
          'department' => $request->input('department'),
          'course' => $request->input('course'),
        ]];
      }

      // Handle violations data
      $violations = [];
      if ($hasMultipleViolations) {
        $violations = json_decode($multipleViolationsData, true);
        if (!$violations || !is_array($violations) || empty($violations)) {
          return redirect()->route('admin.AddViolator')->with('error', 'No violations data provided.');
        }
      } else {
        // Single violation from form field
        $violations = [['description' => $validated['violation']]];
      }

      // Get current user name
      $userName = auth()->user()->fullname ?? 'Admin';
      $createdViolations = [];
      $escalationMessages = [];

      // Create violations for each student-violation combination
      foreach ($students as $student) {
        // Validate each student's data
        if (!isset($student['student_id']) || !isset($student['first_name']) || !isset($student['last_name'])) {
          continue; // Skip invalid student data
        }

        foreach ($violations as $violationData) {
          // Generate unique ID for each violation
          $uniqueID = uniqid();

          $violation = StudentViolation::create([
            'first_name' => $student['first_name'],
            'last_name' => $student['last_name'],
            'student_id' => $student['student_id'],
            'department' => $student['department'] ?? '',
            'course' => $student['course'] ?? '',
            'offense_type' => $validated['offense_type'],
            'violation' => $violationData['description'],
            'ref_num' => $validated['ref_num'],
            'added_by' => $userName,
            'status' => '0', // Pending status
            'unique_id' => $uniqueID,
          ]);

          $createdViolations[] = $violation;

          // Get the violation details to find article reference
          $violationRecord = Violation::where('description', $violation->violation)->first();
          $article = $violationRecord ? $violationRecord->article : null;

          // Create notification for each violation
          ViolationNotif::create([
            'ref_num' => $violation->ref_num ?? 'VIOLATION-' . $violation->id,
            'student_id' => $violation->student_id,
            'status' => 0, // Under review
            'notif' => generateViolationNotification($violation->offense_type, $violation->violation, $article, $violation->added_by),
          ]);
        }

        // Check for escalation if this is a minor violation (only once per student)
        if ($validated['offense_type'] === 'minor') {
          $escalated = $this->checkMinorViolationEscalation($student['student_id']);
          if ($escalated) {
            $escalationMessages[] = $student['first_name'] . ' ' . $student['last_name'];
          }
        }
      }

      // Create success message based on what was added
      $studentCount = count($students);
      $violationCount = count($violations);
      $totalRecords = count($createdViolations);

      if ($hasMultipleStudents && $hasMultipleViolations) {
        $successMessage = "Successfully added {$violationCount} violations for {$studentCount} students ({$totalRecords} total violation records created)!";
      } elseif ($hasMultipleStudents) {
        $successMessage = "Successfully added violation for {$studentCount} students!";
      } elseif ($hasMultipleViolations) {
        $successMessage = "Successfully added {$violationCount} violations for the student!";
      } else {
        $successMessage = 'Violator added successfully!';
      }

      if (!empty($escalationMessages)) {
        $escalatedNames = implode(', ', $escalationMessages);
        $successMessage .= ' 🚨 AUTOMATIC ESCALATION: The following students now have 3 minor violations and MAJOR VIOLATIONS have been automatically created: ' . $escalatedNames;
      }

      return redirect()->route('admin.AddViolator')->with('success', $successMessage);
    } catch (\Exception $e) {
      return redirect()->route('admin.AddViolator')->with('error', 'An error occurred while adding violators: ' . $e->getMessage());
    }
  }

  /**
   * Search students for violator forms (API endpoint)
   */
  public function searchStudents(Request $request)
  {
    $query = $request->get('q', '');

    if (strlen($query) < 2) {
      return response()->json([]);
    }

    // Search in Good Moral system tables (role_account)
    $students = RoleAccount::where(function($q) use ($query) {
        $q->where('student_id', 'LIKE', "%{$query}%")
          ->orWhere('fullname', 'LIKE', "%{$query}%");
      })
      ->where('status', '1') // Only active students
      ->select('student_id', 'fullname', 'department', 'course', 'year_level')
      ->limit(10)
      ->get()
      ->map(function($student) {
        return [
          'student_id' => $student->student_id,
          'fullname' => strtoupper($student->fullname),
          'department' => $student->department ?? 'N/A',
          'course' => $student->course ?? ($student->year_level ? $student->year_level : 'N/A')
        ];
      });

    return response()->json($students);
  }

  public function applicationDashboard()
  {
    // Only show applications that have been approved by the dean
    $applications = GoodMoralApplication::where('application_status', 'LIKE', 'Approved by Dean:%')
      ->whereNotIn('application_status', ['Approved by Administrator', 'Rejected by Administrator'])
      ->get();

    return view('admin.Application', compact('applications'));
  }

  public function approveApplication($id)
  {
    // Find the GoodMoralApplication
    $application = GoodMoralApplication::findOrFail($id);

    // Check if the application has been approved by dean
    if (!str_contains($application->application_status, 'Approved by Dean:')) {
      return redirect()->route('admin.Application')->with('error', 'Application must be approved by Dean first!');
    }

    // Check if already processed by admin
    if (str_contains($application->application_status, 'Approved by Administrator') ||
        str_contains($application->application_status, 'Rejected by Administrator')) {
      return redirect()->route('admin.Application')->with('error', 'Application has already been processed!');
    }

    // Update the application status to 'Approved by Administrator'
    $application->application_status = 'Approved by Administrator';
    $application->save();

    // Generate payment notice automatically
    $receiptService = new ReceiptService();
    $receiptData = $receiptService->generatePaymentNotice($application);

    // Create notification for student - receipt is ready for download
    NotifArchive::create([
      'number_of_copies' => $application->number_of_copies,
      'reference_number' => $application->reference_number,
      'fullname' => $application->fullname,
      'gender' => $application->gender, // Add gender field
      'reason' => $application->reason,
      'student_id' => $application->student_id,
      'department' => $application->department,
      'course_completed' => $application->course_completed,
      'graduation_date' => $application->graduation_date,
      'application_status' => null,
      'is_undergraduate' => $application->is_undergraduate,
      'last_course_year_level' => $application->last_course_year_level,
      'last_semester_sy' => $application->last_semester_sy,
      'certificate_type' => $application->certificate_type,
      'status' => '2', // Status 2 = Approved by Administrator, payment required
    ]);

    return redirect()->route('admin.Application')->with('status', "Application approved successfully! Payment notice {$receiptData['receipt_number']} has been generated for {$application->formatted_payment}. The student will be notified to upload the receipt.");
  }

  public function rejectApplication($id)
  {
    // Find the GoodMoralApplication
    $application = GoodMoralApplication::findOrFail($id);

    // Check if the application has been approved by dean
    if (!str_contains($application->application_status, 'Approved by Dean:')) {
      return redirect()->route('admin.Application')->with('error', 'Application must be approved by Dean first!');
    }

    // Check if already processed by admin
    if (str_contains($application->application_status, 'Approved by Administrator') ||
        str_contains($application->application_status, 'Rejected by Administrator')) {
      return redirect()->route('admin.Application')->with('error', 'Application has already been processed!');
    }

    // Update the application status to 'Rejected by Administrator'
    $application->application_status = 'Rejected by Administrator';
    $application->save();

    // Create notification for student
    NotifArchive::create([
      'number_of_copies' => $application->number_of_copies,
      'reference_number' => $application->reference_number,
      'fullname' => $application->fullname,
      'gender' => $application->gender, // Add gender field
      'reason' => $application->reason,
      'student_id' => $application->student_id,
      'department' => $application->department,
      'course_completed' => $application->course_completed,
      'graduation_date' => $application->graduation_date,
      'application_status' => null,
      'is_undergraduate' => $application->is_undergraduate,
      'last_course_year_level' => $application->last_course_year_level,
      'last_semester_sy' => $application->last_semester_sy,
      'certificate_type' => $application->certificate_type,
      'status' => '-2', // Status -2 = Rejected by Administrator
    ]);

    return redirect()->route('admin.Application')->with('status', 'Application rejected successfully! Student has been notified.');
  }



  public function readyForPrintApplications(Request $request)
  {
    // Get base query for applications that have been approved by admin
    $baseQuery = GoodMoralApplication::whereIn('application_status', [
      'Approved by Administrator',
      'Ready for Moderator Print',
      'Ready for Pickup'
    ])->orderBy('updated_at', 'desc');

    // Get all approved applications (don't filter by receipt - show all approved)
    $allApplicationsQuery = clone $baseQuery;
    $allApplications = $allApplicationsQuery->get();

    // Get paginated applications for each type
    $perPage = 10; // Number of items per page
    $currentPage = $request->get('page', 1);

    // For "All Applications" tab
    $allApplicationsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
      $allApplications->forPage($currentPage, $perPage),
      $allApplications->count(),
      $perPage,
      $currentPage,
      ['path' => $request->url(), 'pageName' => 'page']
    );

    // For "Good Moral" tab
    $goodMoralApplications = $allApplications->where('certificate_type', 'good_moral');
    $goodMoralPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
      $goodMoralApplications->forPage($currentPage, $perPage),
      $goodMoralApplications->count(),
      $perPage,
      $currentPage,
      ['path' => $request->url(), 'pageName' => 'page']
    );

    // For "Residency" tab
    $residencyApplications = $allApplications->where('certificate_type', 'residency');
    $residencyPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
      $residencyApplications->forPage($currentPage, $perPage),
      $residencyApplications->count(),
      $perPage,
      $currentPage,
      ['path' => $request->url(), 'pageName' => 'page']
    );

    // Organize applications by type with pagination
    $applications = [
      'all_good_moral' => $allApplicationsPaginated,
      'good_moral' => $goodMoralPaginated,
      'residency' => $residencyPaginated,
    ];

    return view('admin.ReadyForPrintApplications', compact('applications'));
  }


  public function search(Request $request)
  {
    // Start with dean-approved applications only
    $query = GoodMoralApplication::where('application_status', 'LIKE', 'Approved by Dean:%')
      ->whereNotIn('application_status', ['Approved by Administrator', 'Rejected by Administrator']);

    if ($request->filled('department')) {
      $query->where('department', 'like', '%' . $request->department . '%');
    }
    if ($request->filled('student_id')) {
      $query->where('student_id', 'like', '%' . $request->student_id . '%');
    }
    if ($request->filled('fullname')) {
      $query->where('fullname', 'like', '%' . $request->fullname . '%');
    }
    $applications = $query->paginate(10); // Get paginated results
    return view('admin.Application', compact('applications'));
  }
  public function psgApplication(Request $request)
  {
    $status = $request->get('status'); // Get the filter status from the URL

    // Apply filter based on status
    if ($status == 'approved') {
      $applications = RoleAccount::where('status', '1')->where('account_type', 'psg_officer')->get();
    } elseif ($status == 'rejected') {
      $applications = ArchivedRoleAccount::where('status', '3')->where('account_type', 'psg_officer')->get(); // Default to Pending
    } else {
      $applications = RoleAccount::where('status', '5')->where('account_type', 'psg_officer')->get();
    }

    return view('admin.psgApplication', compact('applications'));
  }

  public function rejectpsg($student_id)
  {
    // Retrieve the application from the RoleAccount table
    $application = RoleAccount::where('student_id', $student_id)->firstOrFail();

    // Prepare the data to be transferred to the new table
    $archivedApplication = new ArchivedRoleAccount();
    $archivedApplication->student_id = $application->student_id;
    $archivedApplication->fullname = $application->fullname;
    $archivedApplication->department = $application->department;
    $archivedApplication->status = '3'; // Rejected status
    $archivedApplication->account_type = $application->account_type;
    $archivedApplication->created_at = $application->created_at; // Ensure you keep the created_at
    $archivedApplication->updated_at = $application->updated_at; // Same for updated_at
    // Add any other fields you need to transfer
    $archivedApplication->save();

    // Delete the original application from the RoleAccount table
    $application->delete();
    $id = $application->student_id;
    // Delete the original registraton from the registration table
    $registration = StudentRegistration::where('student_id', $id)->firstOrFail();
    $registration->delete();


    // Redirect with a success message
    return redirect()->route('admin.psgApplication')->with('status', 'Application rejected and moved to archive.');
  }


  public function approvepsg($student_id)
  {
    $application = RoleAccount::where('student_id', $student_id)->firstOrFail();
    $application->status = '1';
    $applicationStudent = StudentRegistration::where('student_id', $student_id)->firstOrFail();
    $applicationStudent->status = '1';
    $applicationStudent->save();
    $application->save();

    return redirect()->route('admin.psgApplication')->with('status', 'Application approved.');
  }

  public function revokePsg($student_id)
  {
    // Retrieve the application from the RoleAccount table
    $application = RoleAccount::where('student_id', $student_id)->firstOrFail();

    // Prepare the data to be transferred to the archived table
    $archivedApplication = new ArchivedRoleAccount();
    $archivedApplication->student_id = $application->student_id;
    $archivedApplication->fullname = $application->fullname;
    $archivedApplication->department = $application->department;
    $archivedApplication->status = '3'; // Revoked status (same as rejected)
    $archivedApplication->account_type = $application->account_type;
    $archivedApplication->created_at = $application->created_at;
    $archivedApplication->updated_at = now(); // Update timestamp for revocation
    $archivedApplication->save();

    // Delete the original application from the RoleAccount table
    $application->delete();

    // Update the student registration status to revoked
    $registration = StudentRegistration::where('student_id', $student_id)->firstOrFail();
    $registration->status = '3'; // Revoked status
    $registration->save();

    return redirect()->route('admin.psgApplication')->with('status', 'PSG Officer approval has been revoked successfully.');
  }

  public function reconsiderPsg($student_id)
  {
    // Check if this is a rejected application (in archived table)
    $archivedApplication = ArchivedRoleAccount::where('student_id', $student_id)->where('account_type', 'psg_officer')->first();

    if ($archivedApplication) {
      // This is a rejected application - move it back to active table
      $roleAccount = new RoleAccount();
      $roleAccount->student_id = $archivedApplication->student_id;
      $roleAccount->fullname = $archivedApplication->fullname;
      $roleAccount->department = $archivedApplication->department;
      $roleAccount->account_type = $archivedApplication->account_type;
      $roleAccount->status = '5'; // Pending status for reconsideration
      $roleAccount->created_at = $archivedApplication->created_at;
      $roleAccount->updated_at = now();

      // Get additional fields from student registration if they exist
      $studentRegistration = StudentRegistration::where('student_id', $student_id)->first();
      if ($studentRegistration) {
        $roleAccount->email = $studentRegistration->email;
        $roleAccount->password = $studentRegistration->password;
        // Update student registration status
        $studentRegistration->status = '5';
        $studentRegistration->save();
      }

      $roleAccount->save();

      // Remove from archived table
      $archivedApplication->delete();

      return redirect()->route('admin.psgApplication')->with('status', 'Rejected PSG Officer application moved to pending for reconsideration.');
    } else {
      // This might be an approved application - change status back to pending
      $application = RoleAccount::where('student_id', $student_id)->firstOrFail();

      // Change status back to pending for reconsideration
      $application->status = '5'; // Pending status
      $application->save();

      // Update the student registration status to pending
      $applicationStudent = StudentRegistration::where('student_id', $student_id)->firstOrFail();
      $applicationStudent->status = '5'; // Pending status
      $applicationStudent->save();

      return redirect()->route('admin.psgApplication')->with('status', 'PSG Officer application moved to pending for reconsideration.');
    }
  }
  public function deleteViolation($id)
  {
    // Retrieve the application from the RoleAccount table
    $application = Violation::where('id', $id)->firstOrFail();
    // Delete the original application from the RoleAccount table
    $application->delete();
    $violations = Violation::get();
    $violationpage = Violation::paginate(10);
    $status = 'Violation Deleted Successfully.';
    // Redirect with a success message
    return redirect()->route('admin.AddViolation')->with(compact('violations', 'violationpage', 'status'));
  }
  public function updateViolation(Request $request, $id)
  {
    $validated = $request->validate([
      'offense_type' => ['required', 'in:minor,major'],
      'description' => ['required', 'string', 'max:255'],
      // Article field is optional, only validate if submitted
      'article' => ['nullable', 'string', 'max:100'],
    ]);

    $violation = Violation::findOrFail($id);
    $violation->offense_type = $validated['offense_type'];
    $violation->description = $validated['description'];
    
    // Only set the article field if the column exists in the database
    if (Schema::hasColumn('violations', 'article')) {
      $violation->article = $validated['article'];
    }
    
    $violation->save();

    return redirect()->route('admin.AddViolation')->with('success', 'Violation updated successfully.');
  }



  public function GMAApporvedByRegistrar()
  {
    // Fetch legacy applications (HeadOSAApplication)
    $legacyApplications = HeadOSAApplication::where('status', 'pending')->get();

    // Fetch applications that have been approved by the dean
    $deanApprovedApplications = GoodMoralApplication::where('application_status', 'like', 'Approved by Dean:%')
      ->orderBy('updated_at', 'desc')
      ->get();

    // Separate by certificate type
    $goodMoralApplications = $deanApprovedApplications->where('certificate_type', 'good_moral');
    $residencyApplications = $deanApprovedApplications->where('certificate_type', 'residency');

    // Organize applications by type
    $applications = [
      'legacy' => $legacyApplications,
      'good_moral' => $goodMoralApplications,
      'residency' => $residencyApplications,
      'all_dean_approved' => $deanApprovedApplications
    ];

    return view('admin.GMAApporvedByRegistrar', compact('applications'));
  }

  public function rejectGMA($id)
  {
    // Find the HeadOSAApplication and update its status to 'rejected'
    $application = HeadOSAApplication::findOrFail($id);
    $application->status = 'rejected';
    $application->save();

    // Assuming you want to update the 'GoodMoralApplication' using the same student_id
    $student_id = $application->student_id;

    // Retrieve the GoodMoralApplication for the same student
    $goodMoralApplication = GoodMoralApplication::where('student_id', $student_id)->first();

    if ($goodMoralApplication) {
      // Update the application status for GoodMoralApplication
      $goodMoralApplication->application_status = 'Rejected by Administrator';
      $goodMoralApplication->save();
    }

    NotifArchive::create([
      'number_of_copies' => $application->number_of_copies,
      'reference_number' => $application->reference_number,
      'fullname' => $application->fullname,
      'gender' => $goodMoralApplication->gender ?? null,
      'reason' => $application->reason,
      'student_id' => $goodMoralApplication->student_id,
      'department' =>  $goodMoralApplication->department,
      'course_completed' =>  $application->course_completed,  // Allowing this to be null
      'graduation_date' => $application->graduation_date,
      'application_status' => null,
      'is_undergraduate' => $application->is_undergraduate,
      'last_course_year_level' => $application->last_course_year_level,
      'last_semester_sy' => $application->last_semester_sy,
      'certificate_type' => $goodMoralApplication->certificate_type ?? 'good_moral',
      'status' => '-2',
    ]);

    return redirect()->route('admin.GMAApporvedByRegistrar')->with('status', 'Application rejected!');
  }

  public function approveGMA($id)
  {
    // 1. Find the application
    $application = HeadOSAApplication::findOrFail($id);

    // 2. Update the status to 'approved'
    $application->status = 'approved';
    $application->save();

    $student_id = $application->student_id;

    // Retrieve the GoodMoralApplication for the same student
    $goodMoralApplication = GoodMoralApplication::where('student_id', $student_id)->first();
    if ($goodMoralApplication) {
      // Update the application status for GoodMoralApplication
      $goodMoralApplication->application_status = 'Approve by Administrator';
      $goodMoralApplication->save();
    }

    // 3. Get the student from role_account
    $student = $application->student;

    if (!$student) {
      return redirect()->route('admin.GMAApporvedByRegistrar')->with('error', 'Student not found.');
    }

    // 4. Create the head_osa_application record for the single Head OSA
    SecOSAApplication::create([
      'number_of_copies' => $application->number_of_copies,
      'reference_number' => $application->reference_number,
      'student_id' => $student->student_id,
      'fullname' => $student->fullname,
      'department' => $student->department,
      'reason' => $application->formatted_reasons, // Convert array to string
      'course_completed' => $application->course_completed, // New field
      'graduation_date' => $application->graduation_date,   // New field
      'is_undergraduate' => $application->is_undergraduate, // New field
      'last_course_year_level' => $application->last_course_year_level, // New field
      'last_semester_sy' => $application->last_semester_sy,  // New field
      'status' => 'pending', // Default status
    ]);

    NotifArchive::create([
      'number_of_copies' => $application->number_of_copies,
      'reference_number' => $application->reference_number,
      'fullname' => $application->fullname,
      'gender' => $goodMoralApplication->gender ?? null,
      'reason' => $application->reason,
      'student_id' => $student->student_id,
      'department' =>  $student->department,
      'course_completed' =>  $application->course_completed,  // Allowing this to be null
      'graduation_date' => $application->graduation_date,
      'application_status' => null,
      'is_undergraduate' => $application->is_undergraduate,
      'last_course_year_level' => $application->last_course_year_level,
      'last_semester_sy' => $application->last_semester_sy,
      'certificate_type' => $goodMoralApplication->certificate_type ?? 'good_moral',
      'status' => '2',
    ]);

    return redirect()->route('admin.GMAApporvedByRegistrar')->with(
      'status',
      'Application approved and ready to print'
    );
  }

  /**
   * Approve a Good Moral Application (new system).
   */
  public function approveGoodMoralApplication($id)
  {
    $application = GoodMoralApplication::findOrFail($id);

    // Check if application is in correct status (approved by dean)
    if (!str_contains($application->application_status, 'Approved by Dean:')) {
      return redirect()->route('admin.GMAApporvedByRegistrar')->with('error', 'Application must be approved by Dean first.');
    }

    // Update application status
    $application->application_status = 'Approved by Administrator';
    $application->save();

    // Generate payment notice automatically
    $receiptService = new ReceiptService();
    $receiptData = $receiptService->generatePaymentNotice($application);

    // Create notification for student - receipt is ready for download
    NotifArchive::create([
      'number_of_copies' => $application->number_of_copies,
      'reference_number' => $application->reference_number,
      'fullname' => $application->fullname,
      'gender' => $application->gender, // Add gender field
      'reason' => $application->reason,
      'student_id' => $application->student_id,
      'department' => $application->department,
      'course_completed' => $application->course_completed,
      'graduation_date' => $application->graduation_date,
      'application_status' => null,
      'is_undergraduate' => $application->is_undergraduate,
      'last_course_year_level' => $application->last_course_year_level,
      'last_semester_sy' => $application->last_semester_sy,
      'certificate_type' => $application->certificate_type,
      'status' => '2', // Status 2 = Approved by Administrator, payment required
    ]);

    return redirect()->route('admin.GMAApporvedByRegistrar')->with('status', "Application approved! Payment notice {$receiptData['receipt_number']} has been generated for {$application->formatted_payment}. The student will be notified to upload the receipt.");
  }

  /**
   * Reject a Good Moral Application (new system).
   */
  public function rejectGoodMoralApplication($id)
  {
    $application = GoodMoralApplication::findOrFail($id);

    // Check if application is in correct status (approved by dean)
    if (!str_contains($application->application_status, 'Approved by Dean:')) {
      return redirect()->route('admin.GMAApporvedByRegistrar')->with('error', 'Application must be approved by Dean first.');
    }

    // Update application status
    $application->application_status = 'Rejected by Administrator';
    $application->save();

    // Create notification for student
    NotifArchive::create([
      'number_of_copies' => $application->number_of_copies,
      'reference_number' => $application->reference_number,
      'fullname' => $application->fullname,
      'gender' => $application->gender, // Add gender field
      'reason' => $application->reason,
      'student_id' => $application->student_id,
      'department' => $application->department,
      'course_completed' => $application->course_completed,
      'graduation_date' => $application->graduation_date,
      'application_status' => null,
      'is_undergraduate' => $application->is_undergraduate,
      'last_course_year_level' => $application->last_course_year_level,
      'last_semester_sy' => $application->last_semester_sy,
      'certificate_type' => $application->certificate_type,
      'status' => '-2', // Status -2 = Rejected by Administrator
    ]);

    return redirect()->route('admin.GMAApporvedByRegistrar')->with('status', 'Application rejected successfully!');
  }
  public function violation(Request $request)
  {
    // Get the active tab from request, default to 'all'
    $activeTab = $request->get('tab', 'all');

    // Check if grouped view is requested
    $viewMode = $request->get('view', 'individual'); // 'individual' or 'grouped'

    if ($viewMode === 'grouped') {
      return $this->violationGrouped($request);
    }

    // Admin can view ALL violations regardless of who added them
    // Include student relationship for year level information
    $baseQuery = StudentViolation::with('studentAccount')->orderBy('created_at', 'desc');

    // Get violations for each tab with pagination
    $perPage = 10;

    // All violations
    $allViolations = clone $baseQuery;
    $allViolationsPaginated = $allViolations->paginate($perPage, ['*'], 'all_page');

    // Minor violations only
    $minorViolations = clone $baseQuery;
    $minorViolationsPaginated = $minorViolations->where('offense_type', 'minor')
      ->paginate($perPage, ['*'], 'minor_page');

    // Major violations only
    $majorViolations = clone $baseQuery;
    $majorViolationsPaginated = $majorViolations->where('offense_type', 'major')
      ->paginate($perPage, ['*'], 'major_page');

    // Organize violations by type
    $violations = [
      'all' => $allViolationsPaginated,
      'minor' => $minorViolationsPaginated,
      'major' => $majorViolationsPaginated,
    ];

    // Get escalation status for ALL students with minor violations
    $escalationData = [];
    $allStudentsWithMinor = StudentViolation::where('offense_type', 'minor')
      ->select('student_id')
      ->distinct()
      ->get();

    foreach ($allStudentsWithMinor as $studentRecord) {
      // Count ALL minor violations for this student regardless of status
      $minorCount = StudentViolation::where('student_id', $studentRecord->student_id)
        ->where('offense_type', 'minor')
        ->count(); // Count all minor violations (pending, approved, resolved)

      // Determine status color and icon based on count
      $statusColor = '#28a745'; // Green
      $statusIcon = '✅';
      if ($minorCount >= 3) {
        $statusColor = '#dc3545'; // Red
        $statusIcon = '🚨';
      } elseif ($minorCount == 2) {
        $statusColor = '#fd7e14'; // Orange
        $statusIcon = '⚠️';
      } elseif ($minorCount >= 1) {
        $statusColor = '#ffc107'; // Yellow
        $statusIcon = '⚠️';
      }

      $escalationData[$studentRecord->student_id] = [
        'status_color' => $statusColor,
        'status_icon' => $statusIcon,
        'minor_count' => $minorCount,
        'warning_level' => $minorCount >= 3 ? 'critical' : ($minorCount == 2 ? 'high' : ($minorCount == 1 ? 'medium' : 'none'))
      ];
    }

    return view('admin.violation', compact('violations', 'escalationData', 'activeTab'));
  }

  public function getViolationDetails($id)
  {
    try {
      $violation = StudentViolation::findOrFail($id);
      
      return response()->json([
        'success' => true,
        'violation' => [
          'id' => $violation->id,
          'ref_num' => $violation->ref_num,
          'student_id' => $violation->student_id,
          'first_name' => $violation->first_name,
          'last_name' => $violation->last_name,
          'department' => $violation->department,
          'course' => $violation->course,
          'violation' => $violation->violation,
          'description' => $violation->description,
          'offense_type' => $violation->offense_type,
          'status' => $violation->status,
          'added_by' => $violation->added_by,
          'created_at' => $violation->created_at,
          'forwarded_to_admin_at' => $violation->forwarded_to_admin_at,
          'closed_at' => $violation->closed_at,
          'document_path' => $violation->document_path,
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Violation not found or error occurred.'
      ], 404);
    }
  }

  public function markDownloaded($id)
  {
    $violation = StudentViolation::findOrFail($id);
    $violation->downloaded = true;
    $violation->save();

    return back()->with('success', 'Marked as downloaded.');
  }

  public function closeCase($id)
  {
    $violation = StudentViolation::findOrFail($id);

    // For minor violations, check if Dean has approved first
    if ($violation->offense_type === 'minor') {
      // Only allow Admin approval if Dean has already approved (status = 1)
      if ($violation->status != '1') {
        return back()->with('error', 'Minor violations must be approved by the Dean first before Admin can approve.');
      }

      $violation->status = '2'; // Mark as fully resolved/approved
      $violation->save();

      ViolationNotif::create([
        'ref_num' => 'ADMIN-APPROVED',
        'student_id' => $violation->student_id,
        'status' => 1,  // completed status
        'notif' => "Your minor violation case has been fully approved by the Administrator. The case is now resolved and closed. No further action is required.",
      ]);

      return back()->with('success', 'Minor violation approved by Admin! Case fully resolved.');
    } else {
      // For major violations, ensure it has been forwarded by moderator
      if ($violation->status != '1.5') {
        return back()->with('error', 'Major violations must be reviewed by the Moderator first before Admin can close the case.');
      }

      // Close the case
      $violation->status = '2'; // Case closed
      $violation->closed_by = auth()->user()->fullname;
      $violation->closed_at = now();
      $violation->save();

      // Notify the student
      ViolationNotif::create([
        'ref_num' => $violation->ref_num,
        'student_id' => $violation->student_id,
        'status' => 1,  // completed status
        'notif' => "Your major violation case has been resolved and closed by the Administrator. Case Number: {$violation->ref_num}. The disciplinary process is now complete.",
      ]);

      // Notify the Program Coordinator
      $progCoordinators = RoleAccount::where('account_type', 'prog_coor')
        ->where('department', $violation->department)
        ->get();

      foreach ($progCoordinators as $coordinator) {
        // Only create notification if coordinator has a student_id (some staff accounts might not have one)
        if ($coordinator->student_id) {
          ViolationNotif::create([
            'ref_num' => $violation->ref_num,
            'student_id' => $coordinator->student_id,
            'status' => 1,
            'notif' => "Major violation case {$violation->ref_num} for student {$violation->first_name} {$violation->last_name} ({$violation->student_id}) has been closed by the Administrator.",
          ]);
        }
      }

      return back()->with('success', "Major violation case {$violation->ref_num} has been successfully closed.");
    }
  }

  public function violationsearch(Request $request)
  {
    // Get the active tab from request, default to 'all'
    $activeTab = $request->get('tab', 'all');

    // Check if grouped view is requested
    $viewMode = $request->get('view', 'individual'); // 'individual' or 'grouped'

    if ($viewMode === 'grouped') {
      return $this->violationsearchGrouped($request);
    }

    // Admin can search ALL violations regardless of who added them
    // Include student relationship for year level information
    $baseQuery = StudentViolation::with('studentAccount');

    // Apply search filters
    if ($request->filled('ref_num')) {
      $baseQuery->where('ref_num', 'like', '%' . $request->ref_num . '%');
    }
    if ($request->filled('student_id')) {
      $baseQuery->where('student_id', 'like', '%' . $request->student_id . '%');
    }
    if ($request->filled('last_name')) {
      $baseQuery->where('last_name', 'like', '%' . $request->last_name . '%');
    }
    if ($request->filled('course')) {
      $baseQuery->where('course', 'like', '%' . $request->course . '%');
    }
    if ($request->filled('offense_type')) {
      $baseQuery->where('offense_type', $request->offense_type);
    }

    $baseQuery->orderBy('created_at', 'desc');

    // Get violations for each tab with pagination
    $perPage = 10;

    // All violations (with search filters applied)
    $allViolations = clone $baseQuery;
    $allViolationsPaginated = $allViolations->paginate($perPage, ['*'], 'all_page');

    // Minor violations only (with search filters applied)
    $minorViolations = clone $baseQuery;
    $minorViolationsPaginated = $minorViolations->where('offense_type', 'minor')
      ->paginate($perPage, ['*'], 'minor_page');

    // Major violations only (with search filters applied)
    $majorViolations = clone $baseQuery;
    $majorViolationsPaginated = $majorViolations->where('offense_type', 'major')
      ->paginate($perPage, ['*'], 'major_page');

    // Organize violations by type
    $violations = [
      'all' => $allViolationsPaginated,
      'minor' => $minorViolationsPaginated,
      'major' => $majorViolationsPaginated,
    ];

    // Get escalation status for ALL students with minor violations
    $escalationData = [];
    $allStudentsWithMinor = StudentViolation::where('offense_type', 'minor')
      ->select('student_id')
      ->distinct()
      ->get();

    foreach ($allStudentsWithMinor as $studentRecord) {
      // Count ALL minor violations for this student regardless of status
      $minorCount = StudentViolation::where('student_id', $studentRecord->student_id)
        ->where('offense_type', 'minor')
        ->count(); // Count all minor violations (pending, approved, resolved)

      // Determine status color and icon based on count
      $statusColor = '#28a745'; // Green
      $statusIcon = '✅';
      if ($minorCount >= 3) {
        $statusColor = '#dc3545'; // Red
        $statusIcon = '🚨';
      } elseif ($minorCount == 2) {
        $statusColor = '#fd7e14'; // Orange
        $statusIcon = '⚠️';
      } elseif ($minorCount >= 1) {
        $statusColor = '#ffc107'; // Yellow
        $statusIcon = '⚠️';
      }

      $escalationData[$studentRecord->student_id] = [
        'status_color' => $statusColor,
        'status_icon' => $statusIcon,
        'minor_count' => $minorCount,
        'warning_level' => $minorCount >= 3 ? 'critical' : ($minorCount == 2 ? 'high' : ($minorCount == 1 ? 'medium' : 'none'))
      ];
    }

    return view('admin.violation', compact('violations', 'escalationData', 'activeTab'));
  }

  /**
   * Show violations in grouped format
   */
  public function violationGrouped(Request $request)
  {
    // Get the active tab from request, default to 'all'
    $activeTab = $request->get('tab', 'all');

    // Group violations by ref_num, offense_type, and violation
    $baseQuery = StudentViolation::select('ref_num', 'offense_type', 'violation', 'added_by', 'status', 'created_at')
      ->selectRaw('GROUP_CONCAT(DISTINCT student_id ORDER BY student_id SEPARATOR ", ") as student_ids')
      ->selectRaw('GROUP_CONCAT(DISTINCT CONCAT(first_name, " ", last_name) ORDER BY first_name SEPARATOR ", ") as student_names')
      ->selectRaw('GROUP_CONCAT(DISTINCT course ORDER BY course SEPARATOR ", ") as courses')
      ->selectRaw('GROUP_CONCAT(DISTINCT department ORDER BY department SEPARATOR ", ") as departments')
      ->selectRaw('COUNT(*) as student_count')
      ->groupBy('ref_num', 'offense_type', 'violation', 'added_by', 'status', 'created_at')
      ->orderBy('created_at', 'desc');

    // Get violations for each tab with pagination
    $perPage = 10;

    // All violations
    $allViolations = clone $baseQuery;
    $allViolationsPaginated = $allViolations->paginate($perPage, ['*'], 'all_page');

    // Minor violations only
    $minorViolations = clone $baseQuery;
    $minorViolationsPaginated = $minorViolations->where('offense_type', 'minor')
      ->paginate($perPage, ['*'], 'minor_page');

    // Major violations only
    $majorViolations = clone $baseQuery;
    $majorViolationsPaginated = $majorViolations->where('offense_type', 'major')
      ->paginate($perPage, ['*'], 'major_page');

    // Organize violations by type
    $violations = [
      'all' => $allViolationsPaginated,
      'minor' => $minorViolationsPaginated,
      'major' => $majorViolationsPaginated,
    ];

    // Get current violations based on active tab
    $currentViolations = $violations[$activeTab];

    // Get escalation status for ALL students with minor violations (for grouped view)
    $escalationData = [];
    if ($activeTab === 'all' || $activeTab === 'minor') {
      $allStudentsWithMinor = StudentViolation::where('offense_type', 'minor')
        ->select('student_id')
        ->distinct()
        ->get();

      foreach ($allStudentsWithMinor as $studentRecord) {
        $escalationStatus = $this->getEscalationStatusDisplay($studentRecord->student_id);
        $escalationData[$studentRecord->student_id] = $escalationStatus;
      }
    }

    return view('admin.violation-grouped', compact('violations', 'activeTab', 'escalationData', 'currentViolations'));
  }

  /**
   * Search violations in grouped format
   */
  public function violationsearchGrouped(Request $request)
  {
    // Get the active tab from request, default to 'all'
    $activeTab = $request->get('tab', 'all');

    // Group violations by ref_num, offense_type, and violation with search filters
    $baseQuery = StudentViolation::select('ref_num', 'offense_type', 'violation', 'added_by', 'status', 'created_at')
      ->selectRaw('GROUP_CONCAT(DISTINCT student_id ORDER BY student_id SEPARATOR ", ") as student_ids')
      ->selectRaw('GROUP_CONCAT(DISTINCT CONCAT(first_name, " ", last_name) ORDER BY first_name SEPARATOR ", ") as student_names')
      ->selectRaw('GROUP_CONCAT(DISTINCT course ORDER BY course SEPARATOR ", ") as courses')
      ->selectRaw('GROUP_CONCAT(DISTINCT department ORDER BY department SEPARATOR ", ") as departments')
      ->selectRaw('COUNT(*) as student_count');

    // Apply search filters
    if ($request->filled('ref_num')) {
      $baseQuery->where('ref_num', 'like', '%' . $request->ref_num . '%');
    }
    if ($request->filled('student_id')) {
      $baseQuery->where('student_id', 'like', '%' . $request->student_id . '%');
    }
    if ($request->filled('last_name')) {
      $baseQuery->where('last_name', 'like', '%' . $request->last_name . '%');
    }
    if ($request->filled('course')) {
      $baseQuery->where('course', 'like', '%' . $request->course . '%');
    }
    if ($request->filled('offense_type')) {
      $baseQuery->where('offense_type', $request->offense_type);
    }

    $baseQuery->groupBy('ref_num', 'offense_type', 'violation', 'added_by', 'status', 'created_at')
      ->orderBy('created_at', 'desc');

    // Get violations for each tab with pagination
    $perPage = 10;

    // All violations
    $allViolations = clone $baseQuery;
    $allViolationsPaginated = $allViolations->paginate($perPage, ['*'], 'all_page');

    // Minor violations only
    $minorViolations = clone $baseQuery;
    $minorViolationsPaginated = $minorViolations->where('offense_type', 'minor')
      ->paginate($perPage, ['*'], 'minor_page');

    // Major violations only
    $majorViolations = clone $baseQuery;
    $majorViolationsPaginated = $majorViolations->where('offense_type', 'major')
      ->paginate($perPage, ['*'], 'major_page');

    // Organize violations by type
    $violations = [
      'all' => $allViolationsPaginated,
      'minor' => $minorViolationsPaginated,
      'major' => $majorViolationsPaginated,
    ];

    // Get current violations based on active tab
    $currentViolations = $violations[$activeTab];

    // Get escalation status for ALL students with minor violations (for grouped view)
    $escalationData = [];
    if ($activeTab === 'all' || $activeTab === 'minor') {
      $allStudentsWithMinor = StudentViolation::where('offense_type', 'minor')
        ->select('student_id')
        ->distinct()
        ->get();

      foreach ($allStudentsWithMinor as $studentRecord) {
        $escalationStatus = $this->getEscalationStatusDisplay($studentRecord->student_id);
        $escalationData[$studentRecord->student_id] = $escalationStatus;
      }
    }

    return view('admin.violation-grouped', compact('violations', 'activeTab', 'escalationData', 'currentViolations'));
  }

  public function AddAccountnt(Request $request)
  {
    // Start with base query
    $query = RoleAccount::query();

    // Apply search filters if any field is provided
    if ($request->filled('search_name')) {
      $query->where('fullname', 'LIKE', '%' . $request->search_name . '%');
    }

    if ($request->filled('search_student_id')) {
      $query->where('student_id', 'LIKE', '%' . $request->search_student_id . '%');
    }

    if ($request->filled('search_email')) {
      $query->where('email', 'LIKE', '%' . $request->search_email . '%');
    }

    if ($request->filled('search_department')) {
      $query->where('department', $request->search_department);
    }

    if ($request->filled('search_account_type')) {
      $query->where('account_type', $request->search_account_type);
    }

    if ($request->filled('search_status')) {
      $query->where('status', $request->search_status);
    }

    // Order by fullname for consistent results
    $query->orderBy('fullname', 'asc');

    // Paginate results (preserve search parameters in pagination links)
    $students = $query->paginate(10)->appends($request->query());

    return view('admin.AddAccount', compact('students'));
  }

  /**
   * Get account data for editing
   */
  public function editAccount($id)
  {
    try {
      $account = RoleAccount::findOrFail($id);

      return response()->json([
        'success' => true,
        'account' => $account
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Account not found'
      ], 404);
    }
  }

  /**
   * Update account information
   */
  public function updateAccount(Request $request, $id)
  {
    try {
      $account = RoleAccount::findOrFail($id);

      // Validate the request
      $validated = $request->validate([
        'fullname' => 'required|string|max:255',
        'student_id' => 'nullable|string|max:255|unique:role_account,student_id,' . $id,
        'email' => 'required|email|max:255|unique:role_account,email,' . $id,
        'department' => 'required|string|max:255',
        'course' => 'nullable|string|max:255',
        'year_level' => 'nullable|string|max:255',
        'account_type' => 'required|string|in:admin,dean,registrar,sec_osa,prog_coor,psg_officer,student,alumni',
        'status' => 'required|in:0,1',
      ]);

      // Update the account
      $account->update($validated);

      return redirect()->route('admin.AddAccount')
        ->with('success', 'Account updated successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
      return redirect()->route('admin.AddAccount')
        ->withErrors($e->errors())
        ->with('error', 'Validation failed. Please check the form data.');

    } catch (\Exception $e) {
      return redirect()->route('admin.AddAccount')
        ->with('error', 'Error updating account: ' . $e->getMessage());
    }
  }

  /**
   * Delete account
   */
  public function deleteAccount($id)
  {
    try {
      $account = RoleAccount::findOrFail($id);

      // Prevent deletion of the current admin user
      if (auth()->id() === $account->id) {
        return redirect()->route('admin.AddAccount')
          ->with('error', 'You cannot delete your own account!');
      }

      // Store account name for success message
      $accountName = $account->fullname;

      // Delete the account
      $account->delete();

      return redirect()->route('admin.AddAccount')
        ->with('success', "Account for '{$accountName}' has been deleted successfully!");

    } catch (\Exception $e) {
      return redirect()->route('admin.AddAccount')
        ->with('error', 'Error deleting account: ' . $e->getMessage());
    }
  }

  /**
   * Import users from CSV file
   */
  public function importUsers(Request $request)
  {
    $request->validate([
      'csv_file' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    try {
      $file = $request->file('csv_file');
      $path = $file->getRealPath();
      $data = array_map('str_getcsv', file($path));

      // Remove header row
      $header = array_shift($data);

      // Expected CSV format: student_id, first_name, middle_initial, last_name, extension_name, department, course_year, email
      $expectedHeaders = ['student_id', 'first_name', 'middle_initial', 'last_name', 'extension_name', 'department', 'course_year', 'email'];

      // Validate headers
      if (count($header) < 8) { // At least 8 required fields
        return redirect()->back()->with('error', 'CSV file must have 8 columns: student_id, first_name, middle_initial, last_name, extension_name, department, course_year, email');
      }

      $successCount = 0;
      $errorCount = 0;
      $errors = [];
      $defaultPassword = 'student123'; // Default password for all imported students

      foreach ($data as $index => $row) {
        $rowNumber = $index + 2; // +2 because we removed header and arrays are 0-indexed

        // Skip empty rows
        if (empty(array_filter($row))) {
          continue;
        }

        // Ensure we have enough columns
        while (count($row) < 8) {
          $row[] = '';
        }

        // Parse course_year field (e.g., "BSIT 1st Year" -> course: "BSIT", year_level: "1st Year")
        $courseYearField = trim($row[6]);
        $parsedCourseData = $this->parseCourseYear($courseYearField);

        $studentData = [
          'student_id' => trim($row[0]),
          'fname' => trim($row[1]),
          'mname' => trim($row[2]) ?: null,
          'lname' => trim($row[3]),
          'extension' => trim($row[4]) ?: null,
          'department' => trim($row[5]),
          'course' => $parsedCourseData['course'],
          'year_level' => $parsedCourseData['year_level'],
          'email' => trim($row[7]),
          'organization' => null, // No longer in CSV
          'position' => null, // No longer in CSV
        ];

        // Validate required fields
        if (empty($studentData['student_id']) || empty($studentData['fname']) ||
            empty($studentData['lname']) || empty($studentData['email']) ||
            empty($studentData['department'])) {
          $errors[] = "Row {$rowNumber}: Missing required fields (student_id, first_name, last_name, department, email)";
          $errorCount++;
          continue;
        }

        // Normalize email for consistent storage
        $studentData['email'] = strtolower(trim($studentData['email']));

        // Validate email format
        if (!filter_var($studentData['email'], FILTER_VALIDATE_EMAIL)) {
          $errors[] = "Row {$rowNumber}: Invalid email format";
          $errorCount++;
          continue;
        }

        // Check if student_id or email already exists (case-insensitive email check)
        if (RoleAccount::where('student_id', $studentData['student_id'])->exists()) {
          $errors[] = "Row {$rowNumber}: Student ID {$studentData['student_id']} already exists";
          $errorCount++;
          continue;
        }

        if (RoleAccount::whereRaw('LOWER(email) = ?', [$studentData['email']])->exists()) {
          $errors[] = "Row {$rowNumber}: Email {$studentData['email']} already exists";
          $errorCount++;
          continue;
        }

        try {
          // Create fullname
          $fullname = $studentData['lname'] . ', ' . $studentData['fname'];
          if ($studentData['mname']) {
            $fullname .= ' ' . $studentData['mname'];
          }
          if ($studentData['extension']) {
            $fullname .= ' ' . $studentData['extension'];
          }

          // Create student registration record
          StudentRegistration::create([
            'fname' => $studentData['fname'],
            'mname' => $studentData['mname'],
            'lname' => $studentData['lname'],
            'extension' => $studentData['extension'],
            'email' => $studentData['email'],
            'department' => $studentData['department'],
            'course' => $studentData['course'],
            'password' => Hash::make($defaultPassword),
            'student_id' => $studentData['student_id'],
            'status' => '1', // Active
            'account_type' => 'student',
            'year_level' => $studentData['year_level'],
            'organization' => $studentData['organization'],
            'position' => $studentData['position'],
          ]);

          // Create role account record
          RoleAccount::create([
            'fullname' => $fullname,
            'mname' => $studentData['mname'],
            'extension' => $studentData['extension'],
            'department' => $studentData['department'],
            'course' => $studentData['course'],
            'year_level' => $studentData['year_level'],
            'email' => $studentData['email'],
            'password' => Hash::make($defaultPassword),
            'student_id' => $studentData['student_id'],
            'status' => '1', // Active
            'account_type' => 'student',
            'organization' => $studentData['organization'],
            'position' => $studentData['position'],
          ]);

          $successCount++;
        } catch (\Exception $e) {
          $errors[] = "Row {$rowNumber}: Error creating account - " . $e->getMessage();
          $errorCount++;
        }
      }

      $message = "Import completed! {$successCount} students imported successfully.";
      if ($errorCount > 0) {
        $message .= " {$errorCount} errors occurred.";
      }

      if (!empty($errors)) {
        $errorMessage = implode("\n", array_slice($errors, 0, 10)); // Show first 10 errors
        if (count($errors) > 10) {
          $errorMessage .= "\n... and " . (count($errors) - 10) . " more errors.";
        }
        return redirect()->back()->with('import_result', $message)->with('import_errors', $errorMessage);
      }

      return redirect()->back()->with('success', $message);

    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Error processing CSV file: ' . $e->getMessage());
    }
  }

  /**
   * Download CSV template for importing users
   */
  public function downloadTemplate()
  {
    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="student_import_template.csv"',
    ];

    $csvData = [
      ['student_id', 'first_name', 'middle_initial', 'last_name', 'extension_name', 'department', 'course_year', 'email'],
      ['2024-001', 'JUAN', 'D', 'CRUZ', 'JR', 'SITE', 'BSIT 1st Year', 'juan.cruz@spup.edu.ph'],
      ['2024-002', 'MARIA', 'S', 'GARCIA', '', 'SASTE', 'BS Psych 2nd Year', 'maria.garcia@spup.edu.ph'],
      ['2024-003', 'JOSE', '', 'RIZAL', '', 'SBAHM', 'BSA 3rd Year', 'jose.rizal@spup.edu.ph'],
      ['2024-004', 'ANNA', 'M', 'SANTOS', '', 'SNAHS', 'BSN 4th Year', 'anna.santos@spup.edu.ph'],
      ['2024-005', 'MARK', 'J', 'DELA CRUZ', '', 'SITE', 'BS CpE 2nd Year', 'mark.delacruz@spup.edu.ph'],
    ];

    $callback = function() use ($csvData) {
      $file = fopen('php://output', 'w');
      foreach ($csvData as $row) {
        fputcsv($file, $row);
      }
      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }



  /**
   * Generate formal violators report
   */
  public function generateViolatorsReport()
  {
    // Check if user is authenticated
    if (!auth()->check()) {
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
      'generated_by' => auth()->user()->fullname,
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
  public function generateSelectedReport(Request $request)
  {
    $request->validate([
      'academic_year' => 'required|string',
      'report_type' => 'required|in:good_moral_applicants,residency_applicants,minor_violators,major_violators,overall_report,minor_offenses_overall',
      'time_period' => 'nullable|string'
    ]);

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
      'generated_by' => auth()->user()->fullname,
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
      'generated_by' => auth()->user()->fullname,
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
      'generated_by' => auth()->user()->fullname,
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
      'generated_by' => auth()->user()->fullname,
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
      'generated_by' => auth()->user()->fullname,
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
      'generated_by' => auth()->user()->fullname,
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

  /**
   * Mark escalation notification as read
   */
  public function markNotificationAsRead($id)
  {
    try {
      $notification = ViolationNotif::findOrFail($id);

      // Verify this is an escalation notification and belongs to current admin
      if (strpos($notification->ref_num, 'ESCALATION-') === 0 &&
          $notification->student_id === auth()->user()->student_id) {
        $notification->status = 1; // Mark as read
        $notification->save();

        return response()->json(['success' => true]);
      }

      return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Error occurred'], 500);
    }
  }

  /**
   * Print certificate for applications with uploaded receipts
   */
  public function printCertificate($id)
  {
    try {
      Log::info("Admin Print Certificate Started for ID: {$id}");

      // Find the GoodMoralApplication
      $application = GoodMoralApplication::findOrFail($id);
      Log::info("Application found", ['application_id' => $application->id, 'status' => $application->application_status]);

      // Check if the application is approved by administrator, ready for moderator print, or ready for pickup (for reprints)
      if (!in_array($application->application_status, ['Approved by Administrator', 'Ready for Moderator Print', 'Ready for Pickup'])) {
        Log::warning("Application not in printable status", ['status' => $application->application_status]);
        return redirect()->back()->with('error', 'Certificate can only be printed for applications approved by administrator, ready for moderator print, or ready for pickup!');
      }

      // Check if receipt exists
      $receipt = \App\Models\Receipt::where('reference_num', $application->reference_number)->first();
      if (!$receipt || !$receipt->document_path) {
        Log::error("Receipt not found", ['reference_number' => $application->reference_number]);
        return redirect()->back()->with('error', 'Payment receipt not found! Student must upload receipt first.');
      }
      Log::info("Receipt found", ['receipt_id' => $receipt->id]);

      // Get student details
      $studentDetails = \App\Models\RoleAccount::where('student_id', $application->student_id)->first();
      if (!$studentDetails) {
        Log::error("Student details not found", ['student_id' => $application->student_id]);
        return redirect()->back()->with('error', 'Student details not found!');
      }
      Log::info("Student details found", ['student_id' => $studentDetails->student_id, 'account_type' => $studentDetails->account_type]);

      // Get current admin
      $admin = Auth::user();
      Log::info("Admin info", ['admin_id' => $admin->id, 'admin_name' => $admin->fullname]);

      // Prepare data for the PDF
      $data = [
        'title' => $application->certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency',
        'application' => $application,
        'receipt' => $receipt,
        'printed_by' => $admin->fullname,
        'studentDetails' => $studentDetails,
        'studentDetails1' => $application, // The template expects this for semester info
        'print_date' => now()->format('F j, Y'),
        'reasons_array' => $application->reasons_array, // Pass individual reasons
        'number_of_copies' => (int)$application->number_of_copies,
      ];
      Log::info("PDF data prepared", ['title' => $data['title']]);

      // Check if student/alumni has unresolved violations
      $hasUnresolvedViolations = \App\Models\StudentViolation::where('student_id', $application->student_id)
        ->where('status', '!=', 2) // status 2 = fully resolved
        ->exists();
      Log::info("Violation check", ['student_id' => $application->student_id, 'has_violations' => $hasUnresolvedViolations]);

      // Choose view based on certificate type, account type, and violation status
      if ($application->certificate_type === 'good_moral') {
        // Good Moral Certificate (only for those without violations)
        $view = 'pdf.student_certificate';
      } elseif ($application->certificate_type === 'residency') {
        // Certificate of Residency - different templates for students vs alumni
        if ($studentDetails->account_type === 'student') {
          // Students with violations get simple residency certificate
          $view = 'pdf.student_residency_certificate';
        } else {
          // Alumni with violations get the existing residency certificate
          $view = 'pdf.other_certificate';
        }
      } else {
        // Fallback logic for legacy applications
        if ($hasUnresolvedViolations) {
          $view = $studentDetails->account_type === 'student' ? 'pdf.student_residency_certificate' : 'pdf.other_certificate';
        } else {
          $view = 'pdf.student_certificate';
        }
      }
      Log::info("Template selected", [
        'view' => $view,
        'certificate_type' => $application->certificate_type,
        'account_type' => $studentDetails->account_type,
        'has_violations' => $hasUnresolvedViolations
      ]);

      // Check if view exists
      if (!view()->exists($view)) {
        Log::error("View does not exist", ['view' => $view]);
        return redirect()->back()->with('error', "PDF template '{$view}' not found!");
      }

      // Generate PDF
      Log::info("Starting PDF generation");
      $pdf = Pdf::loadView($view, $data);
      $pdf->setPaper('letter', 'portrait');
      Log::info("PDF generated successfully");

      // Update application status to ready for pickup (only on first print)
      $isReprint = $application->application_status === 'Ready for Pickup';
      if (in_array($application->application_status, ['Approved by Administrator', 'Ready for Moderator Print'])) {
        $application->application_status = 'Ready for Pickup';
        $application->save();
        Log::info("First print - Application status updated to Ready for Pickup");

        // Create notification for student - certificate printed and ready for pickup
        NotifArchive::create([
          'number_of_copies' => $application->number_of_copies,
          'reference_number' => $application->reference_number,
          'fullname' => $application->fullname,
          'gender' => $application->gender, // Add gender field
          'reason' => $application->reason,
          'student_id' => $application->student_id,
          'department' => $application->department,
          'course_completed' => $application->course_completed,
          'graduation_date' => $application->graduation_date,
          'application_status' => null,
          'is_undergraduate' => $application->is_undergraduate,
          'last_course_year_level' => $application->last_course_year_level,
          'last_semester_sy' => $application->last_semester_sy,
          'certificate_type' => $application->certificate_type,
          'status' => '5', // Status 5 = Certificate printed and ready for pickup
        ]);
        Log::info("First print - Notification created for student");
      } else {
        Log::info("Reprint - status and notification unchanged");
      }

      // Generate filename
      $certificateType = $application->certificate_type === 'good_moral' ? 'GoodMoral' : 'Residency';
      $reprintSuffix = $isReprint ? '_REPRINT' : '';
      $filename = "{$certificateType}_Certificate_{$application->student_id}_{$application->reference_number}{$reprintSuffix}.pdf";
      Log::info("Filename generated", ['filename' => $filename, 'is_reprint' => $isReprint]);

      // Try to download the PDF
      try {
        Log::info("Attempting PDF download");
        $response = $pdf->download($filename);
        Log::info("PDF download successful");
        return $response;
      } catch (\Exception $downloadError) {
        Log::error("PDF download failed", ['error' => $downloadError->getMessage()]);

        // Try alternative: stream the PDF
        try {
          Log::info("Attempting PDF stream as fallback");
          return $pdf->stream($filename);
        } catch (\Exception $streamError) {
          Log::error("PDF stream also failed", ['error' => $streamError->getMessage()]);
          throw new \Exception("Both download and stream failed: " . $downloadError->getMessage() . " | " . $streamError->getMessage());
        }
      }

    } catch (\Exception $e) {
      Log::error("Admin Print Certificate Error", [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
      ]);
      return redirect()->back()->with('error', 'An error occurred while printing the certificate: ' . $e->getMessage());
    }
  }

  /**
   * Download certificate for already printed applications (allows multiple downloads)
   */
  public function downloadCertificate($id)
  {
    try {
      Log::info("Admin Download Certificate Started for ID: {$id}");

      // Find the GoodMoralApplication
      $application = GoodMoralApplication::findOrFail($id);
      Log::info("Application found", ['application_id' => $application->id, 'status' => $application->application_status]);

      // Check if the application is ready for pickup (already printed), approved by admin, or ready for moderator print
      if (!in_array($application->application_status, ['Ready for Pickup', 'Approved by Administrator', 'Ready for Moderator Print'])) {
        Log::warning("Application not available for download", ['status' => $application->application_status]);
        return redirect()->route('admin.readyForPrintApplications')->with('error', 'Certificate is not available for download!');
      }

      // Check if receipt exists
      $receipt = \App\Models\Receipt::where('reference_num', $application->reference_number)->first();
      if (!$receipt || !$receipt->document_path) {
        Log::error("Receipt not found", ['reference_number' => $application->reference_number]);
        return redirect()->route('admin.readyForPrintApplications')->with('error', 'Payment receipt not found!');
      }
      Log::info("Receipt found", ['receipt_id' => $receipt->id]);

      // Get student details
      $studentDetails = \App\Models\RoleAccount::where('student_id', $application->student_id)->first();
      if (!$studentDetails) {
        Log::error("Student details not found", ['student_id' => $application->student_id]);
        return redirect()->route('admin.readyForPrintApplications')->with('error', 'Student details not found!');
      }
      Log::info("Student details found", ['student_id' => $studentDetails->student_id, 'account_type' => $studentDetails->account_type]);

      // Get current admin
      $admin = Auth::user();
      Log::info("Admin info", ['admin_id' => $admin->id, 'admin_name' => $admin->fullname]);

      // Prepare data for the PDF
      $data = [
        'title' => $application->certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency',
        'application' => $application,
        'receipt' => $receipt,
        'printed_by' => $admin->fullname,
        'studentDetails' => $studentDetails,
        'studentDetails1' => $application, // The template expects this for semester info
        'print_date' => now()->format('F j, Y'),
        'reasons_array' => $application->reasons_array, // Pass individual reasons
        'number_of_copies' => (int)$application->number_of_copies,
      ];
      Log::info("PDF data prepared", ['title' => $data['title']]);

      // Check if student/alumni has unresolved violations
      $hasUnresolvedViolations = \App\Models\StudentViolation::where('student_id', $application->student_id)
        ->where('status', '!=', 2) // status 2 = fully resolved
        ->exists();
      Log::info("Violation check", ['student_id' => $application->student_id, 'has_violations' => $hasUnresolvedViolations]);

      // Choose view based on certificate type, account type, and violation status
      if ($application->certificate_type === 'good_moral') {
        // Good Moral Certificate (only for those without violations)
        $view = 'pdf.student_certificate';
      } elseif ($application->certificate_type === 'residency') {
        // Certificate of Residency - different templates for students vs alumni
        if ($studentDetails->account_type === 'student') {
          // Students with violations get simple residency certificate
          $view = 'pdf.student_residency_certificate';
        } else {
          // Alumni with violations get the existing residency certificate
          $view = 'pdf.other_certificate';
        }
      } else {
        // Fallback logic for legacy applications
        if ($hasUnresolvedViolations) {
          $view = $studentDetails->account_type === 'student' ? 'pdf.student_residency_certificate' : 'pdf.other_certificate';
        } else {
          $view = 'pdf.student_certificate';
        }
      }
      Log::info("Template selected", [
        'view' => $view,
        'certificate_type' => $application->certificate_type,
        'account_type' => $studentDetails->account_type,
        'has_violations' => $hasUnresolvedViolations
      ]);

      // Check if view exists
      if (!view()->exists($view)) {
        Log::error("View does not exist", ['view' => $view]);
        return redirect()->route('admin.readyForPrintApplications')->with('error', "PDF template '{$view}' not found!");
      }

      // Generate PDF
      Log::info("Starting PDF generation");
      $pdf = Pdf::loadView($view, $data);
      $pdf->setPaper('letter', 'portrait');
      Log::info("PDF generated successfully");

      // Generate filename
      $certificateType = $application->certificate_type === 'good_moral' ? 'GoodMoral' : 'Residency';
      $filename = "{$certificateType}_Certificate_{$application->student_id}_{$application->reference_number}.pdf";
      Log::info("Filename generated", ['filename' => $filename]);

      // Try to download the PDF
      try {
        Log::info("Attempting PDF download");
        $response = $pdf->download($filename);
        Log::info("PDF download successful");
        return $response;
      } catch (\Exception $downloadError) {
        Log::error("PDF download failed", ['error' => $downloadError->getMessage()]);

        // Try alternative: stream the PDF
        try {
          Log::info("Attempting PDF stream as fallback");
          return $pdf->stream($filename);
        } catch (\Exception $streamError) {
          Log::error("PDF stream also failed", ['error' => $streamError->getMessage()]);
          throw new \Exception("Both download and stream failed: " . $downloadError->getMessage() . " | " . $streamError->getMessage());
        }
      }

    } catch (\Exception $e) {
      Log::error("Admin Download Certificate Error", [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
      ]);
      return redirect()->route('admin.readyForPrintApplications')->with('error', 'An error occurred while downloading the certificate: ' . $e->getMessage());
    }
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

  /**
   * Store report generation record in database
   */
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
        'generated_by' => auth()->user()->fullname ?? auth()->user()->email ?? 'System',
        'generated_by_role' => auth()->user()->account_type ?? 'Unknown',
        'generated_at' => now(),
        'status' => 'completed',
      ]);
    } catch (\Exception $e) {
      Log::warning('Failed to store report record: ' . $e->getMessage());
    }
  }

  public function databaseSummary()
  {
    // Check authentication
    if (!auth()->check()) {
      return redirect()->route('login');
    }

    // 1. USERS & ACCOUNTS
    $totalUsers = RoleAccount::count();
    $usersByRole = RoleAccount::select('account_type', DB::raw('count(*) as count'))
      ->groupBy('account_type')
      ->pluck('count', 'account_type')
      ->toArray();
    
    $archivedUsersByRole = ArchivedRoleAccount::select('account_type', DB::raw('count(*) as count'))
      ->groupBy('account_type')
      ->pluck('count', 'account_type')
      ->toArray();

    // 2. STUDENT REGISTRATIONS
    $totalStudents = StudentRegistration::count();
    $departments = ['SITE', 'SASTE', 'SBAHM', 'SNAHS', 'SOM', 'GRADSCH'];
    $studentsByDepartment = [];
    $totalStudentCount = 0;

    foreach ($departments as $dept) {
      $total = StudentRegistration::where('department', $dept)->count();
      $male = StudentRegistration::where('department', $dept)->where('gender', 'Male')->count();
      $female = StudentRegistration::where('department', $dept)->where('gender', 'Female')->count();
      
      $studentsByDepartment[$dept] = [
        'total' => $total,
        'male' => $male,
        'female' => $female,
        'percentage' => 0 // Will calculate after
      ];
      $totalStudentCount += $total;
    }

    // Calculate percentages
    foreach ($studentsByDepartment as $dept => &$data) {
      $data['percentage'] = $totalStudentCount > 0 ? ($data['total'] / $totalStudentCount) * 100 : 0;
    }

    // 3. GOOD MORAL APPLICATIONS
    $totalApplications = GoodMoralApplication::count();
    $applicationsByStatus = GoodMoralApplication::select('status', DB::raw('count(*) as count'))
      ->groupBy('status')
      ->pluck('count', 'status')
      ->toArray();

    $applicationsByDepartment = [];
    foreach ($departments as $dept) {
      $applicationsByDepartment[$dept] = [
        'total' => GoodMoralApplication::where('department', $dept)->count(),
        'pending' => GoodMoralApplication::where('department', $dept)->where('status', 'Pending')->count(),
        'approved' => GoodMoralApplication::where('department', $dept)
          ->where('status', 'Approved by Administrator')->count(),
        'rejected' => GoodMoralApplication::where('department', $dept)
          ->whereIn('status', ['Rejected by SEC-OSA', 'Rejected by HEAD-OSA', 'Rejected by DEAN', 'Rejected by Administrator'])->count(),
        'ready' => GoodMoralApplication::where('department', $dept)->where('status', 'Ready for Pickup')->count(),
      ];
    }

    // 4. VIOLATIONS
    $totalViolations = StudentViolation::count();
    $violationsByType = [
      'minor' => StudentViolation::where('offense_type', 'minor')->count(),
      'major' => StudentViolation::where('offense_type', 'major')->count(),
    ];

    $violationsByStatus = [
      'resolved' => StudentViolation::where('status', 2)->count(),
      'pending' => StudentViolation::where('status', '!=', 2)->count(),
    ];

    $violationsByDepartment = [];
    foreach ($departments as $dept) {
      $violationsByDepartment[$dept] = [
        'total' => StudentViolation::where('department', $dept)->count(),
        'minor' => StudentViolation::where('department', $dept)->where('offense_type', 'minor')->count(),
        'major' => StudentViolation::where('department', $dept)->where('offense_type', 'major')->count(),
        'resolved' => StudentViolation::where('department', $dept)->where('status', 2)->count(),
        'pending' => StudentViolation::where('department', $dept)->where('status', '!=', 2)->count(),
      ];
    }

    // 5. RECEIPTS & PAYMENTS
    $totalReceipts = DB::table('receipt')->count();
    $totalRevenue = DB::table('receipt')->sum('amount') ?? 0;
    $averagePayment = $totalReceipts > 0 ? $totalRevenue / $totalReceipts : 0;

    $receiptsByPaymentMethod = DB::table('receipt')
      ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
      ->groupBy('payment_method')
      ->get()
      ->mapWithKeys(function ($item) use ($totalRevenue) {
        return [$item->payment_method => [
          'count' => $item->count,
          'total' => $item->total ?? 0,
          'percentage' => $totalRevenue > 0 ? ($item->total / $totalRevenue) * 100 : 0
        ]];
      })
      ->toArray();

    // 6. ARCHIVED RECORDS
    $archivedAccounts = ArchivedRoleAccount::count();
    $archivedNotifications = NotifArchive::count();

    // 7. DATABASE TABLES OVERVIEW
    $databaseTables = [
      ['name' => 'users', 'count' => DB::table('users')->count(), 'description' => 'System user authentication'],
      ['name' => 'role_account', 'count' => RoleAccount::count(), 'description' => 'User roles and accounts'],
      ['name' => 'student_registrations', 'count' => StudentRegistration::count(), 'description' => 'Student registration records'],
      ['name' => 'good_moral_applications', 'count' => GoodMoralApplication::count(), 'description' => 'Good moral certificate applications'],
      ['name' => 'student_violations', 'count' => StudentViolation::count(), 'description' => 'Student violation records'],
      ['name' => 'violations', 'count' => Violation::count(), 'description' => 'Violation types and definitions'],
      ['name' => 'violation_notifs', 'count' => ViolationNotif::count(), 'description' => 'Violation notifications'],
      ['name' => 'receipt', 'count' => DB::table('receipt')->count(), 'description' => 'Payment receipts'],
      ['name' => 'courses', 'count' => DB::table('courses')->count(), 'description' => 'Course catalog'],
      ['name' => 'academic_years', 'count' => AcademicYear::count(), 'description' => 'Academic year records'],
      ['name' => 'head_osa_applications', 'count' => HeadOSAApplication::count(), 'description' => 'Head OSA approval records'],
      ['name' => 'dean_applications', 'count' => DeanApplication::count(), 'description' => 'Dean approval records'],
      ['name' => 'sec_osa_applications', 'count' => SecOSAApplication::count(), 'description' => 'SEC OSA approval records'],
      ['name' => 'archived_role_accounts', 'count' => ArchivedRoleAccount::count(), 'description' => 'Archived user accounts'],
      ['name' => 'notifarchives', 'count' => NotifArchive::count(), 'description' => 'Archived notifications'],
      ['name' => 'generated_reports', 'count' => GeneratedReport::count(), 'description' => 'Generated report history'],
    ];

    return view('admin.database-summary', compact(
      'totalUsers',
      'usersByRole',
      'archivedUsersByRole',
      'totalStudents',
      'studentsByDepartment',
      'totalApplications',
      'applicationsByStatus',
      'applicationsByDepartment',
      'totalViolations',
      'violationsByType',
      'violationsByStatus',
      'violationsByDepartment',
      'totalReceipts',
      'totalRevenue',
      'averagePayment',
      'receiptsByPaymentMethod',
      'archivedAccounts',
      'archivedNotifications',
      'databaseTables'
    ));
  }

  public function downloadDatabaseSummaryPDF()
  {
    // This will be implemented with PDF generation
    return response()->json(['message' => 'PDF download feature coming soon']);
  }

  public function downloadDatabaseSummaryExcel()
  {
    // This will be implemented with Excel generation
    return response()->json(['message' => 'Excel download feature coming soon']);
  }

  /**
   * Helper method to generate PDF with proper header/footer on every page
   */
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
