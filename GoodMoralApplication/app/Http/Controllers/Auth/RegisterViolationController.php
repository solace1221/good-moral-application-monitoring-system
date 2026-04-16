<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use App\Models\User;
use App\Models\StudentRegistration;
use App\Models\RoleAccount;
use App\Models\StudentViolation;
use App\Traits\DateFilterTrait;
use App\Traits\ViolationEscalationTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Http\Requests\StoreViolationReportRequest;
use App\Helpers\CourseHelper;
use App\Services\DashboardStatsService;
use App\Services\ViolationService;
use Illuminate\Support\Facades\Log;

class RegisterViolationController extends Controller
{
  use DateFilterTrait, ViolationEscalationTrait;

  /**
   * Display the registration view.
   */

  public function __construct(
    private ViolationService $violationService
  ) {
    // Role check will be done in middleware or individual methods
  }

  public function dashboard(Request $request): View
  {
    // Check if user is PSG officer
    if (Auth::user()->account_type !== 'psg_officer') {
      abort(403, 'Unauthorized access.');
    }

    // Get frequency filter from request
    $frequency = $request->get('frequency', 'all');

    // Get current PSG Officer from RoleAccount (consistent with store method)
    $roleAccount = RoleAccount::where('email', Auth::user()->email)->first();
    $currentUser = $roleAccount->fullname;

    // Get statistics for minor violations (PSG Officers only see violations they added)
    $minorPending = $this->applyDateFilter(StudentViolation::where('status', '!=', 2)->where('offense_type', 'minor')->where('added_by', $currentUser), $frequency)->count();
    $minorResolved = $this->applyDateFilter(StudentViolation::where('status', '=', 2)->where('offense_type', 'minor')->where('added_by', $currentUser), $frequency)->count();
    $minorTotal = $minorPending + $minorResolved;
    $minorResolvedPercentage = $minorTotal > 0 ? round(($minorResolved / $minorTotal) * 100, 1) : 0;

    // Get violations by department for PSG Officer view (only their violations)
    $departments = DashboardStatsService::VIOLATION_DEPARTMENTS;
    $violationsByDept = [];
    foreach ($departments as $dept) {
      $violationsByDept[$dept] = $this->applyDateFilter(StudentViolation::where('offense_type', 'minor')->where('added_by', $currentUser)->where('department', $dept), $frequency)->count();
    }

    return view('PsgOfficer.dashboard', compact(
      'minorPending',
      'minorResolved',
      'minorTotal',
      'minorResolvedPercentage',
      'violationsByDept',
      'frequency'
    ) + [
      'frequencyOptions' => $this->getFrequencyOptions(),
      'frequencyLabel' => $this->getFrequencyLabel($frequency)
    ]);
  }

  public function create(): View
  {
    // Check if user is PSG officer
    if (Auth::user()->account_type !== 'psg_officer') {
      abort(403, 'Unauthorized access.');
    }

    return view('PsgOfficer.psg-add-violation');
  }

  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(StoreViolationReportRequest $request): RedirectResponse
  {
    // Debug logging
    Log::info('PSG Violation Store Method Called', [
      'user_id' => Auth::id(),
      'request_data' => $request->all()
    ]);

    $currentUser = RoleAccount::where('email', Auth::user()->email)->first();
    $userName = $currentUser->fullname;
    $uniqueID = $currentUser->student_id;

    Log::info('Validation passed successfully');

    $violationParts = explode('|', $request->violation);
    $offenseType = $violationParts[0] ?? '';
    $description = $violationParts[1] ?? '';

    // PSG Officers can only issue minor violations - force offense type to minor
    if ($currentUser->account_type === 'psg_officer') {
      $offenseType = 'minor';
    }

    $studentId = $request->student_id;
    $successMessage = 'Violator Added Successfully!';

    if ($request->violation !== 'Others') {
      $violation = StudentViolation::create([
        'first_name' => $request->first_name,
        'department' => $request->department,
        'course' => $request->course,
        'last_name' => $request->last_name,
        'violation' => $description,
        'student_id' => $studentId,
        'added_by' => $userName,
        'status' => 'Reported',
        'offense_type' => $offenseType,
        'unique_id' => $uniqueID,
        'ref_num' => $uniqueID,
      ]);
    } else {
      $violation = StudentViolation::create([
        'department' => $request->department,
        'course' => $request->course,
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'violation' => $request->others,
        'student_id' => $studentId,
        'added_by' => $userName,
        'status' => 'Reported',
        'offense_type' => 'minor', // PSG Officers can only issue minor violations
        'unique_id' => $uniqueID,
        'ref_num' => $uniqueID,
      ]);
    }

    // Log violation creation
    Log::info('Violation Created Successfully', [
      'violation_id' => $violation->id,
      'student_id' => $violation->student_id,
      'offense_type' => $violation->offense_type,
      'added_by' => $violation->added_by
    ]);

    // Create notification for the student about the violation
    try {
      $this->violationService->createViolationNotification($violation);
    } catch (\Throwable $e) {
      Log::warning('Violation notification failed for PSG officer path', [
        'violation_id' => $violation->id,
        'student_id' => $violation->student_id,
        'error' => $e->getMessage(),
      ]);
    }

    // Check for escalation if this is a minor violation
    if ($violation->offense_type === 'minor') {
      $escalated = $this->checkMinorViolationEscalation($studentId);
      if ($escalated) {
        $successMessage = 'Violator Added Successfully! 🚨 AUTOMATIC ESCALATION: This student now has 3 minor violations. A MAJOR VIOLATION has been automatically created and all admins have been notified.';
      }
    }

    return redirect()->route('PsgOfficer.PsgAddViolation')->with('success', $successMessage);
  }

  public function violator()
  {
    // PSG Officers can only view violations they have added
    $currentUser = RoleAccount::where('email', Auth::user()->email)->first();
    $students = StudentViolation::where('added_by', $currentUser->fullname)
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    // Return the view instead of redirecting
    return view('PsgOfficer.violator', compact('students'));
  }
  public function ViolatorDashboard()
  {
    // PSG Officers can only issue minor violations
    $violations = Violation::where('offense_type', 'minor')->where('status', 'active')->get();

    $coursesByDepartment = CourseHelper::getCoursesByDepartment();

    return view('PsgOfficer.psg-add-violation', compact('violations', 'coursesByDepartment'));
  }

  /**
   * Check student violations for escalation warning
   */
  public function checkStudentViolations($studentId)
  {
    try {
      $warning = $this->getEscalationWarning($studentId);

      if ($warning['warning_level'] !== 'none') {
        return response()->json([
          'success' => true,
          'warning' => $warning
        ]);
      }

      return response()->json(['success' => false]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
  }
}
