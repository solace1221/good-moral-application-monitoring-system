<?php

namespace App\Http\Controllers\GoodMoral;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\RoleAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Traits\RoleCheck;
use App\Models\StudentViolation;
use App\Models\GoodMoralApplication;
use App\Services\ViolationService;
use App\Services\GoodMoralWorkflowService;

use App\Models\ViolationNotif;
use Illuminate\Support\Facades\Log;

class ProgramCoordinatorController extends Controller
{
  use RoleCheck;

  public function __construct(
    private ViolationService $violationService,
    private GoodMoralWorkflowService $workflowService
  ) {
    $this->checkRole(['prog_coor']);
  }

  // Dashboard and minor violations removed - Program Coordinators only view major violations from their department
  // public function dashboard()
  // {
  //   //Applicants per department
  //   $site = StudentViolation::where('department', 'SITE')->count();
  //   $saste = StudentViolation::where('department', 'SASTE')->count();
  //   $sbahm = StudentViolation::where('department', 'SBAHM')->count();
  //   $snahs = StudentViolation::where('department', 'SNAHS')->count();

  //   //For Pie Chart stats
  //   $minorpending = StudentViolation::where('status', '!=', 2)->where('offense_type', 'minor')->count();
  //   $minorcomplied = StudentViolation::where('status', '=', 2)->where('offense_type', 'minor')->count();
  //   $majorpending = StudentViolation::where('status', '!=', 2)->where('offense_type', 'major')->count();
  //   $majorcomplied = StudentViolation::where('status', '=', 2)->where('offense_type', 'major')->count();
  //   //Pageinate
  //   $violationpage = Violation::paginate(10);
  //   return view('prog_coor.dashboard', compact('site', 'sbahm', 'saste', 'snahs', 'minorpending', 'minorcomplied', 'majorpending', 'majorcomplied', 'violationpage'));
  // }

  // public function minor()
  // {
  //   return view('prog_coor.minor'); // Ensure this view exists
  // }

  public function major()
  {
    $userDepartment = Auth::user()->department;

    // Get major violations for this department
    $students = StudentViolation::where('department', $userDepartment)
      ->where('offense_type', 'major')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    // Count violations by status for dashboard stats
    extract($this->violationService->getMajorStatusCounts([$userDepartment]));

    return view('prog_coor.major', compact('students', 'pendingCount', 'proceedingsUploadedCount', 'closedCount'));
  }

  public function CoorMajorSearch(Request $request)
  {
    $userDepartment = Auth::user()->department;

    $query = StudentViolation::where('department', $userDepartment)
      ->where('offense_type', 'major');

    if ($request->filled('ref_num')) {
      $query->where('ref_num', 'like', "%{$request->ref_num}%");
    }
    if ($request->filled('student_id')) {
      $query->where('student_id', 'like', "%{$request->student_id}%");
    }
    if ($request->filled('last_name')) {
      $query->where('last_name', 'like', "%{$request->last_name}%");
    }
    $students = $query->paginate(10);

    // Count violations by status for dashboard stats
    extract($this->violationService->getMajorStatusCounts([$userDepartment]));

    return view('prog_coor.major', compact('students', 'pendingCount', 'proceedingsUploadedCount', 'closedCount'));
  }

  /**
   * Download proceedings document (view only for Program Coordinators)
   */
  public function downloadProceedings($id)
  {
    $violation = StudentViolation::findOrFail($id);
    $progCoordinator = Auth::user();

    // Ensure this violation belongs to the coordinator's department
    if ($violation->department !== $progCoordinator->department) {
      abort(403, 'Unauthorized access to proceedings.');
    }

    if (!$violation->document_path || !Storage::disk('public')->exists($violation->document_path)) {
      return redirect()->back()->with('error', 'Proceedings document not found.');
    }

    return response()->download(Storage::disk('public')->path($violation->document_path));
  }

  public function minor()
  {
    $userDepartment = Auth::user()->department;
    $tab = request('tab', 'pending');

    $query = StudentViolation::where('department', $userDepartment)
      ->where('offense_type', 'minor')
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
    $baseQuery = StudentViolation::where('department', $userDepartment)->where('offense_type', 'minor');
    $pendingCount = (clone $baseQuery)->whereIn('status', ['Reported', 'Under Review'])->count();
    $approvedCount = (clone $baseQuery)->where('status', 'Approved')->count();
    $completedCount = (clone $baseQuery)->whereIn('status', ['Complied', 'Closed'])->count();
    $declinedCount = (clone $baseQuery)->where('status', 'Declined')->count();

    return view('prog_coor.minor', compact('students', 'tab', 'pendingCount', 'approvedCount', 'completedCount', 'declinedCount'));
  }

  public function approveMinorViolation($id)
  {
    $user = Auth::user();
    $violation = StudentViolation::findOrFail($id);

    if ($violation->department !== $user->department) {
      abort(403, 'Unauthorized access.');
    }

    if ($violation->offense_type !== 'minor' || !in_array($violation->status, ['Reported', 'Under Review'])) {
      return back()->with('error', 'This violation cannot be approved.');
    }

    $violation->status = 'Approved';
    $violation->reviewed_by = $user->fullname;
    $violation->reviewed_role = 'program_coordinator';
    $violation->reviewed_at = now();
    $violation->save();

    ViolationNotif::create([
      'ref_num' => $violation->ref_num,
      'student_id' => $violation->student_id,
      'status' => 0,
      'notif' => "Your minor violation has been approved by Program Coordinator {$user->fullname} ({$user->department}). The case is now forwarded to the Admin for finalization.",
    ]);

    return back()->with('success', 'Minor violation approved! Forwarded to Admin for finalization.');
  }

  public function declineMinorViolation(Request $request, $id)
  {
    $request->validate([
      'decline_reason' => 'required|string|max:1000',
    ]);

    $user = Auth::user();
    $violation = StudentViolation::findOrFail($id);

    if ($violation->department !== $user->department) {
      abort(403, 'Unauthorized access.');
    }

    if ($violation->offense_type !== 'minor' || !in_array($violation->status, ['Reported', 'Under Review'])) {
      return back()->with('error', 'This violation cannot be declined.');
    }

    $violation->status = 'Declined';
    $violation->decline_reason = $request->decline_reason;
    $violation->reviewed_by = $user->fullname;
    $violation->reviewed_role = 'program_coordinator';
    $violation->reviewed_at = now();
    $violation->save();

    ViolationNotif::create([
      'ref_num' => $violation->ref_num,
      'student_id' => $violation->student_id,
      'status' => 0,
      'notif' => "Your minor violation has been declined by Program Coordinator {$user->fullname} ({$user->department}). Reason: {$request->decline_reason}",
    ]);

    return back()->with('success', 'Minor violation has been declined.');
  }

  /**
   * Display certificate applications for the program coordinator's department.
   */
  public function application()
  {
    try {
      $progCoor = Auth::user();

      if (!$progCoor) {
        return redirect()->route('login')->with('error', 'Please login to access applications.');
      }

      // Fetch Good Moral Applications that need approval (approved by registrar)
      $goodMoralApplications = GoodMoralApplication::approvedByRegistrar()
        ->where('department', $progCoor->department)
        ->where('certificate_type', 'good_moral')
        ->whereNotNull('application_status')
        ->orderBy('updated_at', 'desc')
        ->get();

      // Fetch Residency Applications that need approval
      $residencyApplications = GoodMoralApplication::approvedByRegistrar()
        ->where('department', $progCoor->department)
        ->where('certificate_type', 'residency')
        ->whereNotNull('application_status')
        ->orderBy('updated_at', 'desc')
        ->get();

      // Combine all applications for total count
      $allApplications = $goodMoralApplications->merge($residencyApplications);

      $applications = [
        'good_moral' => $goodMoralApplications,
        'residency' => $residencyApplications,
        'all_new' => $allApplications,
      ];

      return view('prog_coor.application', [
        'applications' => $applications,
        'department' => $progCoor->department,
      ]);

    } catch (\Exception $e) {
      Log::error('Program Coordinator Application Error', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'user_id' => Auth::id(),
      ]);

      return redirect()->route('prog_coor.major')->with('error', 'Unable to load applications. Please try again.');
    }
  }

  /**
   * Approve a Good Moral Application.
   */
  public function approveGoodMoral($id)
  {
    $progCoor = Auth::user();
    if (!$progCoor) {
      return redirect()->route('login')->with('error', 'Authentication error');
    }

    try {
      $application = GoodMoralApplication::findOrFail($id);

      if ($application->department !== $progCoor->department) {
        return redirect()->route('prog_coor.application')->with('error', 'Unauthorized access to application.');
      }

      if (!str_contains($application->application_status, 'Approved By Registrar') &&
          !str_contains($application->application_status, 'Approved by Registrar')) {
        return redirect()->route('prog_coor.application')->with('error', 'Application is not ready for approval.');
      }

      $result = $this->workflowService->approveByProgCoor($application, $progCoor->fullname);

      $successMessage = "Good Moral application approved! Payment notice generated. Student has been notified to pay at Business Affairs and upload their receipt.";

      if (request()->ajax() || request()->wantsJson()) {
        return response()->json(['success' => true, 'message' => $successMessage]);
      }

      return redirect()->route('prog_coor.application')->with('status', $successMessage);
    } catch (\Exception $e) {
      if (request()->ajax() || request()->wantsJson()) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
      }

      return redirect()->route('prog_coor.application')->with('error', 'Error approving application: ' . $e->getMessage());
    }
  }

  /**
   * Reject a Good Moral Application.
   */
  public function rejectGoodMoral($id)
  {
    $progCoor = Auth::user();
    $application = GoodMoralApplication::findOrFail($id);

    if ($application->department !== $progCoor->department) {
      return redirect()->route('prog_coor.application')->with('error', 'Unauthorized access to application.');
    }

    if (!str_contains($application->application_status, 'Approved By Registrar') &&
        !str_contains($application->application_status, 'Approved by Registrar')) {
      return redirect()->route('prog_coor.application')->with('error', 'Application is not ready for action.');
    }

    $this->workflowService->rejectByProgCoor($application, $progCoor->fullname);

    return redirect()->route('prog_coor.application')->with('status', 'Good Moral application rejected successfully!');
  }

  /**
   * Get application details for AJAX requests.
   */
  public function getApplicationDetails($id)
  {
    $application = GoodMoralApplication::findOrFail($id);

    return response()->json([
      'rejection_reason' => $application->rejection_reason,
      'rejection_details' => $application->rejection_details,
      'rejected_by' => $application->rejected_by,
      'rejected_at' => $application->rejected_at,
      'action_history' => $application->action_history,
    ]);
  }
}
