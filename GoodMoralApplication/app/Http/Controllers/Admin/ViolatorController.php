<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMultipleViolatorsRequest;
use App\Http\Requests\StoreViolatorRequest;
use App\Traits\ViolationEscalationTrait;
use Illuminate\Http\Request;

use App\Models\Violation;
use App\Models\StudentViolation;
use App\Models\RoleAccount;
use App\Models\StudentRegistration;
use App\Helpers\CourseHelper;
use App\Services\ViolationService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ViolatorController extends Controller
{
  use ViolationEscalationTrait;

  public function __construct(
    private ViolationService $violationService
  ) {}

  /**
   * Generate a unique reference number in the format VIO-YYYY-XXXX
   */
  private function generateReferenceNumber(): string
  {
    $year = date('Y');
    $prefix = "VIO-{$year}-";

    $latest = StudentViolation::where('ref_num', 'LIKE', "{$prefix}%")
      ->orderByRaw('CAST(SUBSTRING(ref_num, ' . (strlen($prefix) + 1) . ') AS UNSIGNED) DESC')
      ->value('ref_num');

    if ($latest) {
      $lastSequence = (int) substr($latest, strlen($prefix));
      $nextSequence = $lastSequence + 1;
    } else {
      $nextSequence = 1;
    }

    return $prefix . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
  }

  public function AddViolatorDashboard()
  {
    $violations = Violation::active()->get();

    $coursesByDepartment = CourseHelper::getCoursesByDepartment();

    return view('admin.add-violator', compact('violations', 'coursesByDepartment'));
  }

  public function storeViolator(StoreViolatorRequest $request)
  {
    Log::info('storeViolator called', [
      'all_input' => $request->except('_token'),
      'method' => $request->method(),
    ]);

    $validated = $request->validated();

    // Generate unique ID
    $uniqueID = uniqid();

    // Generate reference number
    $referenceNumber = $this->generateReferenceNumber();

    // Get current user name
    $userName = Auth::user()->fullname ?? 'Admin';

    $violation = StudentViolation::create([
      'first_name' => $validated['first_name'],
      'last_name' => $validated['last_name'],
      'student_id' => $validated['student_id'],
      'department' => $validated['department'],
      'course' => $validated['course'],
      'offense_type' => $validated['offense_type'],
      'violation' => $validated['violation'],
      'ref_num' => $referenceNumber,
      'added_by' => $userName,
      'status' => $validated['offense_type'] === 'minor' ? 'Reported' : '0',
      'unique_id' => $uniqueID,
      'case_type' => 'single',
      'group_size' => 1,
    ]);

    Log::info('StudentViolation created', ['id' => $violation->id, 'student_id' => $violation->student_id]);

    // Create notification for the student about the violation
    try {
      $this->violationService->createViolationNotification($violation);
    } catch (\Exception $e) {
      Log::error('Failed to create violation notification', [
        'violation_id' => $violation->id,
        'error' => $e->getMessage(),
      ]);
    }

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
    $violations = Violation::active()->get();
    return view('admin.add-multiple-violators', compact('violations'));
  }

  /**
   * Store multiple violators
   */
  public function storeMultipleViolators(StoreMultipleViolatorsRequest $request)
  {
    // Prevent duplicate submissions within 10 seconds
    $cacheKey = 'multiple_violators_' . Auth::id() . '_' . md5(json_encode($request->all()));
    if (Cache::has($cacheKey)) {
      Log::warning('Duplicate submission prevented:', [
        'user_id' => Auth::id(),
        'cache_key' => $cacheKey,
        'timestamp' => now()->toDateTimeString()
      ]);
      return redirect()->route('admin.AddMultipleViolators')->with('warning', 'Duplicate submission prevented. Please wait before submitting again.');
    }

    // Set cache to prevent duplicates for 10 seconds
    Cache::put($cacheKey, true, 10);

    // Enhanced Debug: Log all request data
    Log::info('Multiple Violators Request Started:', [
      'timestamp' => now()->toDateTimeString(),
      'user_id' => Auth::id(),
      'user_name' => Auth::user()->fullname ?? 'Unknown',
      'all_data' => $request->all(),
      'offense_type' => $request->input('offense_type'),
      'violation' => $request->input('violation'),
      'multiple_violations_data' => $request->input('multiple_violations_data'),
      'student_ids' => $request->input('student_ids'),
      'ref_num' => $request->input('ref_num'),
      'request_method' => $request->method(),
      'request_url' => $request->url(),
    ]);

    $validated = $request->validated();
    $hasMultipleViolations = !empty($validated['multiple_violations_data']);

    Log::info('Validated Data:', $validated);
    Log::info('Has Multiple Violations:', ['value' => $hasMultipleViolations]);

    // Determine violations to process
    $violationsToProcess = [];

    if ($hasMultipleViolations) {
      // Multiple violations mode
      $multipleViolations = json_decode($validated['multiple_violations_data'], true);
      if (is_array($multipleViolations) && !empty($multipleViolations)) {
        $violationsToProcess = $multipleViolations;
        Log::info('Processing multiple violations:', $violationsToProcess);
      } else {
        Log::error('Invalid multiple violations data:', ['data' => $validated['multiple_violations_data']]);
        return redirect()->back()->withErrors(['multiple_violations_data' => 'Invalid violations data provided.']);
      }
    } else {
      // Single violation mode
      if (!empty($validated['violation'])) {
        $violationsToProcess = [['description' => $validated['violation']]];
        Log::info('Processing single violation:', $violationsToProcess);
      } else {
        return redirect()->back()->withErrors(['violation' => 'Please select a violation.']);
      }
    }

    if (empty($violationsToProcess)) {
      Log::error('No violations to process');
      return redirect()->back()->withErrors(['violation' => 'Please select at least one violation.']);
    }

    $userName = Auth::user()->fullname ?? 'Admin';
    $createdViolations = [];
    $escalationMessages = [];
    $groupSize = count($validated['student_ids']);
    $referenceNumber = $this->generateReferenceNumber();

    // Pre-fetch all students in a single query
    $students = RoleAccount::whereIn('student_id', $validated['student_ids'])
      ->get()
      ->keyBy('student_id');

    foreach ($validated['student_ids'] as $studentId) {
      // Find student info from pre-fetched collection
      $student = $students->get($studentId);
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
          'ref_num' => $referenceNumber,
          'added_by' => $userName,
          'status' => $validated['offense_type'] === 'minor' ? 'Reported' : '0',
          'unique_id' => uniqid(),
          'case_type' => $groupSize > 1 ? 'group' : 'single',
          'group_size' => $groupSize,
        ]);

        // Create notification
        try {
          $this->violationService->createViolationNotification($violation);
        } catch (\Exception $e) {
          Log::error('Failed to create violation notification', ['violation_id' => $violation->id, 'error' => $e->getMessage()]);
        }
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
   * Search students for violator forms (API endpoint)
   */
  public function searchStudents(Request $request)
  {
    $query = $request->get('q', '');

    if (strlen($query) < 2) {
      return response()->json([]);
    }

    // Query student_registrations so ALL registered students are included,
    // regardless of their account status. The old query used role_account with
    // a status='active' filter, which silently excluded inactive/pending students.
    $students = StudentRegistration::where(function($q) use ($query) {
        $q->where('student_id', 'LIKE', "%{$query}%")
          ->orWhere('fname', 'LIKE', "%{$query}%")
          ->orWhere('lname', 'LIKE', "%{$query}%")
          ->orWhereRaw("CONCAT(fname, ' ', lname) LIKE ?", ["%{$query}%"])
          ->orWhere('email', 'LIKE', "%{$query}%")
          ->orWhere('department', 'LIKE', "%{$query}%");
      })
      ->where('account_type', 'student')
      ->select('student_id', 'fname', 'mname', 'lname', 'department', 'course', 'year_level')
      ->orderBy('lname')
      ->limit(20)
      ->get()
      ->map(function($student) {
        $fullname = trim(strtoupper(
          $student->fname . ' '
          . ($student->mname ? $student->mname . ' ' : '')
          . $student->lname
        ));
        return [
          'student_id' => $student->student_id,
          'fullname'   => $fullname,
          'department' => $student->department ?? 'N/A',
          'course'     => $student->course ?? ($student->year_level ?? 'N/A'),
        ];
      });

    return response()->json($students);
  }
}
