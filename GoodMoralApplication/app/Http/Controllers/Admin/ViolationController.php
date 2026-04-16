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
    $displayMode = $request->get('display_mode', 'all');

    $baseQuery = StudentViolation::with('studentAccount');

    // Search by student name or ID
    if ($request->filled('search')) {
      $search = $request->search;
      $baseQuery->where(function ($q) use ($search) {
        $q->where('student_id', 'like', '%' . $search . '%')
          ->orWhere('first_name', 'like', '%' . $search . '%')
          ->orWhere('last_name', 'like', '%' . $search . '%')
          ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%']);
      });
    }

    // Filter by department
    if ($request->filled('department')) {
      $baseQuery->where('department', 'like', '%' . $request->department . '%');
    }

    // Filter by violation type (minor / major)
    if ($request->filled('offense_type')) {
      $baseQuery->where('offense_type', $request->offense_type);
    }

    // Filter by status — use explicit string values since status is a TEXT column
    if ($request->filled('status')) {
      if ($request->status === 'resolved') {
        $baseQuery->where(function ($q) {
          $q->whereIn('status', ['2', 'Complied', 'Closed']);
        });
      } elseif ($request->status === 'pending') {
        $baseQuery->where(function ($q) {
          $q->whereIn('status', ['0', '1', '1.5', 'Reported', 'Under Review', 'Approved']);
        });
      }
    }

    // Display mode filtering based on how many students share the same ref_num
    if ($displayMode === 'grouped') {
      // Group cases: ref_num appears more than once (multiple students in same incident)
      $baseQuery->whereNotNull('ref_num')
        ->where('ref_num', '!=', '')
        ->whereIn('ref_num', function ($sub) {
          $sub->select('ref_num')
            ->from('student_violations')
            ->whereNotNull('ref_num')
            ->where('ref_num', '!=', '')
            ->groupBy('ref_num')
            ->havingRaw('COUNT(*) > 1');
        })
        ->orderBy('ref_num');
    } elseif ($displayMode === 'individual') {
      // Individual cases: ref_num appears exactly once (single student) OR ref_num is null
      $baseQuery->where(function ($q) {
        $q->whereNull('ref_num')
          ->orWhere('ref_num', '')
          ->orWhereIn('ref_num', function ($sub) {
            $sub->select('ref_num')
              ->from('student_violations')
              ->whereNotNull('ref_num')
              ->where('ref_num', '!=', '')
              ->groupBy('ref_num')
              ->havingRaw('COUNT(*) = 1');
          });
      });
      $baseQuery->orderBy('created_at', 'desc');
    } else {
      // All: no display mode filtering
      $baseQuery->orderBy('created_at', 'desc');
    }

    $perPage = 10;
    $allViolationsPaginated = $baseQuery->paginate($perPage, ['*'], 'page');

    $violations = [
      'all'   => $allViolationsPaginated,
      'minor' => $allViolationsPaginated,
      'major' => $allViolationsPaginated,
    ];

    $activeTab = 'all';
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
          'forwarded_to_admin_at' => $violation->forwarded_to_admin_at
              ?? ($violation->ref_num
                  ? StudentViolation::where('ref_num', $violation->ref_num)
                      ->whereNotNull('forwarded_to_admin_at')
                      ->value('forwarded_to_admin_at')
                  : null),
          'closed_at' => $violation->closed_at,
          'document_path' => $violation->document_path
              ?? ($violation->ref_num
                  ? StudentViolation::where('ref_num', $violation->ref_num)
                      ->whereNotNull('document_path')
                      ->value('document_path')
                  : null),
          'reviewed_by' => $violation->reviewed_by,
          'reviewed_role' => $violation->reviewed_role,
          'reviewed_at' => $violation->reviewed_at,
          'finalized_by' => $violation->finalized_by,
          'finalized_at' => $violation->finalized_at,
          'decline_reason' => $violation->decline_reason,
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

  public function closeCase(Request $request, $id)
  {
    $violation = StudentViolation::findOrFail($id);
    $userName = Auth::user()->fullname ?? 'Admin';

    // For minor violations, check if reviewer has approved first
    if ($violation->offense_type === 'minor') {
      if ($violation->status !== 'Approved') {
        return back()->with('error', 'Minor violations must be approved by a reviewer (Dean or Program Coordinator) before Admin can finalize.');
      }

      $action = $request->input('action', 'complied');
      $violation->status = $action === 'closed' ? 'Closed' : 'Complied';
      $violation->finalized_by = $userName;
      $violation->finalized_at = now();
      $violation->save();

      $statusLabel = $violation->status === 'Closed' ? 'closed' : 'marked as complied';

      ViolationNotif::create([
        'ref_num' => $violation->ref_num,
        'student_id' => $violation->student_id,
        'status' => 1,
        'notif' => "Your minor violation case has been {$statusLabel} by the Administrator. No further action is required.",
      ]);

      return back()->with('success', "Minor violation {$statusLabel} successfully.");
    } else {
      // For major violations, ensure it has been forwarded by moderator
      if ($violation->status != '1') {
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

  public function declineCase(Request $request, $id)
  {
    $request->validate([
      'decline_reason' => 'required|string|max:1000',
    ]);

    $violation = StudentViolation::findOrFail($id);

    if ($violation->status == '2' || $violation->status == '3' || in_array($violation->status, ['Complied', 'Closed', 'Declined'])) {
      return back()->with('error', 'This case has already been resolved.');
    }

    $violation->status = '3'; // Declined
    $violation->decline_reason = $request->decline_reason;
    $violation->closed_by = Auth::user()->fullname;
    $violation->closed_at = now();
    $violation->save();

    ViolationNotif::create([
      'ref_num' => $violation->ref_num,
      'student_id' => $violation->student_id,
      'status' => 0,
      'notif' => "Your violation case has been declined by the Administrator. Case Reference: {$violation->ref_num}.",
    ]);

    return back()->with('success', 'Violation case has been declined.');
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
    // For grouped view, use the dedicated grouped search method
    if ($request->get('view', 'individual') === 'grouped') {
      return $this->violationsearchGrouped($request);
    }

    // Individual view: delegate to violation() which now applies all filters
    return $this->violation($request);
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
    if ($request->filled('department')) {
      $baseQuery->where('department', 'like', '%' . $request->department . '%');
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
