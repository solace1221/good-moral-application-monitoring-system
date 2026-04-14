<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreViolationTypeRequest;
use App\Models\RoleAccount;
use App\Models\StudentViolation;
use App\Models\Violation;
use App\Models\ViolationNotif;
use App\Services\ViolationService;
use App\Traits\ViolationEscalationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ViolationController extends Controller
{
  use ViolationEscalationTrait;

  protected ViolationService $violationService;

  public function __construct(ViolationService $violationService)
  {
    $this->violationService = $violationService;
  }

  public function create(StoreViolationTypeRequest $request)
  {
    $validated = $request->validated();

    Violation::create([
      'offense_type' => $validated['offense_type'],
      'description' => $validated['description'],
      'article' => $validated['article'],
      'status' => 'active',
    ]);
    return redirect()->back()->with('success', 'Violation successfully recorded.');
  }
  public function AddViolationDashboard()
  {
    $violations = Violation::get();
    $violationpage = Violation::paginate(10);
    return view('admin.add-violation', compact('violations', 'violationpage'));
  }

  public function deleteViolation($id)
  {
    $violation = Violation::findOrFail($id);

    // If it's in use, archive instead of delete
    if ($violation->isInUse()) {
      $violation->update(['status' => 'inactive']);
      return redirect()->route('admin.AddViolation')
        ->with('success', 'Violation type has been archived because it is referenced by existing student records.');
    }

    // Not in use — allow permanent deletion
    $violation->delete();
    return redirect()->route('admin.AddViolation')
      ->with('success', 'Violation type deleted successfully.');
  }

  public function archiveViolation($id)
  {
    $violation = Violation::findOrFail($id);
    $violation->update(['status' => 'inactive']);

    return redirect()->route('admin.AddViolation')
      ->with('success', 'Violation type has been archived.');
  }

  public function restoreViolation($id)
  {
    $violation = Violation::findOrFail($id);
    $violation->update(['status' => 'active']);

    return redirect()->route('admin.AddViolation')
      ->with('success', 'Violation type has been restored.');
  }
  public function updateViolation(StoreViolationTypeRequest $request, $id)
  {
    $validated = $request->validated();

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
    $escalationData = $this->violationService->getAllEscalationData();

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
      $violation->closed_by = Auth::user()->fullname;
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

  /**
   * Download proceedings document for a violation (Admin has access to all)
   */
  public function downloadProceedings($id)
  {
    $violation = StudentViolation::findOrFail($id);

    if (!$violation->document_path || !Storage::disk('public')->exists($violation->document_path)) {
      return redirect()->back()->with('error', 'Proceedings document not found.');
    }

    return response()->download(Storage::disk('public')->path($violation->document_path));
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
    if ($request->filled('search')) {
      $search = $request->search;
      $baseQuery->where(function ($q) use ($search) {
        $q->where('student_id', 'like', '%' . $search . '%')
          ->orWhere('first_name', 'like', '%' . $search . '%')
          ->orWhere('last_name', 'like', '%' . $search . '%')
          ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%']);
      });
    }
    if ($request->filled('course')) {
      $baseQuery->where('course', 'like', '%' . $request->course . '%');
    }
    if ($request->filled('offense_type')) {
      $baseQuery->where('offense_type', $request->offense_type);
    }
    if ($request->filled('status')) {
      if ($request->status === 'resolved') {
        $baseQuery->where('status', 2);
      } elseif ($request->status === 'pending') {
        $baseQuery->where('status', '<', 2);
      }
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
    $escalationData = $this->violationService->getAllEscalationData();

    return view('admin.violation', compact('violations', 'escalationData', 'activeTab'));
  }

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
}
