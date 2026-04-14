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
}
