<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ViolationEscalationTrait;
use Illuminate\Http\Request;

use App\Models\Violation;
use App\Models\StudentViolation;
use App\Models\ViolationNotif;
use App\Models\RoleAccount;
use App\Helpers\ViolationHelper;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ViolatorController extends Controller
{
  use ViolationEscalationTrait;

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

    return view('admin.add-violator', compact('violations', 'coursesByDepartment'));
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
    $userName = Auth::user()->fullname ?? 'Admin';

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
    $offenseType = (string) $violation->offense_type;
    $description = (string) $violation->violation;
    $addedBy = (string) ($violation->added_by ?? 'Admin');

    ViolationNotif::create([
        'ref_num' => $violation->ref_num ?? 'VIOLATION-' . $violation->id,
        'student_id' => $violation->student_id,
        'status' => 0,
        'notif' => ViolationHelper::generateViolationNotification(
            $offenseType,
            $description,
            $article,
            $addedBy
        ),
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
    return view('admin.add-multiple-violators', compact('violations'));
  }

  /**
   * Store multiple violators
   */
  public function storeMultipleViolators(Request $request)
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
            'notif' => ViolationHelper::generateViolationNotification(
                $violation->offense_type,
                (string) $violation->violation,
                $article,
                $violation->added_by
            ),
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
      $userName = Auth::user()->fullname ?? 'Admin';
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
            'status' => 0,
            'notif' => ViolationHelper::generateViolationNotification(
                $violation->offense_type,
                (string) $violation->violation,
                $article,
                $violation->added_by
            ),
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
}
