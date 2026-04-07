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
class RegisterViolationController extends Controller
{
  use DateFilterTrait, ViolationEscalationTrait;

  /**
   * Display the registration view.
   */

  public function __construct()
  {
    // Role check will be done in middleware or individual methods
  }

  public function dashboard(Request $request): View
  {
    // Check if user is PSG officer
    if (auth()->user()->account_type !== 'psg_officer') {
      abort(403, 'Unauthorized access.');
    }

    // Get frequency filter from request
    $frequency = $request->get('frequency', 'all');

    // Get current PSG Officer
    $currentUser = auth()->user()->fullname;

    // Get statistics for minor violations (PSG Officers only see violations they added)
    $minorPending = $this->applyDateFilter(StudentViolation::where('status', '!=', 2)->where('offense_type', 'minor')->where('added_by', $currentUser), $frequency)->count();
    $minorResolved = $this->applyDateFilter(StudentViolation::where('status', '=', 2)->where('offense_type', 'minor')->where('added_by', $currentUser), $frequency)->count();
    $minorTotal = $minorPending + $minorResolved;
    $minorResolvedPercentage = $minorTotal > 0 ? round(($minorResolved / $minorTotal) * 100, 1) : 0;

    // Get violations by department for PSG Officer view (only their violations)
    $departments = ['SITE', 'SASTE', 'SBAHM', 'SNAHS'];
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
    if (auth()->user()->account_type !== 'psg_officer') {
      abort(403, 'Unauthorized access.');
    }

    return view('PsgOfficer.PsgAddViolation');
  }

  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): RedirectResponse
  {
    // Debug logging
    \Log::info('PSG Violation Store Method Called', [
      'user_id' => Auth::id(),
      'request_data' => $request->all()
    ]);

    $currentUserId = Auth::id();
    $currentUser = RoleAccount::find($currentUserId);
    $userName = $currentUser->fullname;
    $uniqueID = $currentUser->student_id;

    try {
      $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'student_id' => ['required', 'string', 'max:20'],
        'violation' => ['required', 'string', 'max:255'],
        'department' => ['required', 'string', 'max:255'],
        'course' => ['nullable', 'string', 'max:255'],
        'others' => ['nullable', 'string', 'max:255'],
      ]);

      \Log::info('Validation passed successfully');
    } catch (\Illuminate\Validation\ValidationException $e) {
      \Log::error('Validation failed', [
        'errors' => $e->errors(),
        'request_data' => $request->all()
      ]);
      throw $e;
    }

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
        'status' => '0',
        'offense_type' => $offenseType,
        'unique_id' => $uniqueID,
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
        'status' => '0',
        'offense_type' => 'minor', // PSG Officers can only issue minor violations
        'unique_id' => $uniqueID,
      ]);
    }

    // Log violation creation
    \Log::info('Violation Created Successfully', [
      'violation_id' => $violation->id,
      'student_id' => $violation->student_id,
      'offense_type' => $violation->offense_type,
      'added_by' => $violation->added_by
    ]);

    // Get the violation details to find article reference
    $violationRecord = \App\Models\Violation::where('description', $violation->violation)->first();
    $article = $violationRecord ? $violationRecord->article : null;

    // Create notification for the student about the violation using new format
    \App\Models\ViolationNotif::create([
      'ref_num' => $violation->ref_num ?? 'VIOLATION-' . $violation->id,
      'student_id' => $violation->student_id,
      'status' => 0, // Under review
      'notif' => generateViolationNotification($violation->offense_type, $violation->violation, $article, $violation->added_by),
    ]);

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
    $currentUser = auth()->user()->fullname;
    $students = StudentViolation::where('added_by', $currentUser)
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    // Return the view instead of redirecting
    return view('PsgOfficer.Violator', compact('students'));
  }
  public function ViolatorDashboard()
  {
    // PSG Officers can only issue minor violations
    $violations = Violation::where('offense_type', 'minor')->get();

    $coursesByDepartment = [
      'SITE' => ['BSIT', 'BLIS', 'BS ENSE', 'BS CpE', 'BSCE'],
      'SBAHM' => ['BSA', 'BSE', 'BSBAMM', 'BSBA MFM', 'BSBA MOP', 'BSMA', 'BSHM', 'BSTM', 'BSPDMI'],
      'SASTE' => ['BAELS', 'BS Psych', 'BS Bio', 'BSSW', 'BSPA', 'BS Bio MB', 'BSEd', 'BEEd', 'BPEd'],
      'SNAHS' => ['BSN', 'BSPh', 'BSMT', 'BSPT', 'BSRT'],
    ];

    return view('PsgOfficer.PsgAddViolation', compact('violations', 'coursesByDepartment'));
  }

  public function PsgViolation()
  {
    // PSG Officers can only view violations they have added (minor violations only)
    $currentUser = auth()->user()->fullname;
    $violations = StudentViolation::where('offense_type', 'minor')
      ->where('added_by', $currentUser)
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return view('PsgOfficer.PsgViolation', compact('violations'));
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
