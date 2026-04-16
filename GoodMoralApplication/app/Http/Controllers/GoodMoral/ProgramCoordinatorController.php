<?php

namespace App\Http\Controllers\GoodMoral;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\RoleAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Traits\RoleCheck;
use App\Models\StudentViolation;
use App\Services\ViolationService;

use App\Models\ViolationNotif;

class ProgramCoordinatorController extends Controller
{
  use RoleCheck;

  public function __construct(
    private ViolationService $violationService
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
}
