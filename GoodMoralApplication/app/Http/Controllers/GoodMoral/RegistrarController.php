<?php

namespace App\Http\Controllers\GoodMoral;
use App\Http\Controllers\Controller;

use App\Models\GoodMoralApplication;
use App\Models\NotifArchive;
use App\Models\DeanApplication;
use App\Traits\RoleCheck;
use Illuminate\Http\Request;
use App\Services\NotificationArchiveService;

class RegistrarController extends Controller
{
  use RoleCheck;

  protected NotificationArchiveService $notifService;

  public function __construct(NotificationArchiveService $notifService)
  {
    $this->notifService = $notifService;
    // Re-enable role check (but it's disabled in the trait for now)
    $this->checkRole(['registrar']);
  }

  public function goodMoralApplication()
  {
    // Check if user is authenticated
    $user = auth()->user();

    if (!$user) {
      return redirect()->route('login')->with('error', 'Please log in first.');
    }

    // Get the 'perPage' parameter from the request, default to 10 if not provided
    $perPage = request()->get('perPage', 10);

    // Get filter status from request
    $status = request()->get('status', 'pending');

    // Get applications based on status filter (latest first)
    if ($status === 'all') {
      $applications = GoodMoralApplication::with('student')->orderBy('created_at', 'desc')->paginate($perPage);
    } else {
      $applications = GoodMoralApplication::with('student')->where('status', $status)->orderBy('created_at', 'desc')->paginate($perPage);
    }

    // Count pending applications for notification bell
    $pendingCount = GoodMoralApplication::where('status', 'pending')->count();

    // Get recent processed applications for the history list
    $recentProcessed = GoodMoralApplication::with('student')->whereIn('status', ['approved', 'rejected'])
      ->orderBy('updated_at', 'desc')
      ->limit(5)
      ->get();

    // Handle AJAX requests for notification updates
    if (request()->ajax || request()->get('ajax')) {
      return response()->json([
        'pendingCount' => $pendingCount,
        'status' => 'success'
      ]);
    }

    return view('registrar.goodMoralApplication', compact('applications', 'pendingCount', 'recentProcessed'));
  }

  /**
   * Get notification counts for registrar sidebar
   */
  public function getNotificationCounts()
  {
    // Count pending Good Moral applications that need registrar approval
    $pendingApplications = GoodMoralApplication::where('status', 'pending')->count();

    return response()->json([
      'pendingApplications' => $pendingApplications,
    ]);
  }


  /**
   * Approve a Good Moral Certificate application.
   *
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function approve($id)
  {
    // 1. Find the application
    $application = GoodMoralApplication::findOrFail($id);

    // 2. Update the status to 'approved'
    $registrar = auth()->user();
    $application->status = 'approved';
    $application->application_status = 'Approved By Registrar ' . $registrar->fullname;
    $application->save();

    // 3. Get the student from role_account
    $student = $application->student;

    if (!$student) {
      return redirect()->route('registrar.goodMoralApplication')->with('error', 'Student not found.');
    }

    // 4. Create the dean record for the single Head OSA
    DeanApplication::create([
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

    $this->notifService->createFromApplication($application, '1');


    return redirect()->route('registrar.goodMoralApplication')->with('status', 'Application approved and forwarded to Dean Office.');
  }

  /**
   * Reject a Good Moral Certificate application.
   *
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function reject(Request $request, $id)
  {
    // Custom validation rules
    $rules = [
      'rejection_reason' => 'required|string|max:255',
      'rejection_details' => 'nullable|string|max:1000',
      'specify_reason' => 'nullable|string|max:255',
    ];

    // Make specify_reason required if "Others: specify" is selected
    if ($request->rejection_reason === 'Others: specify') {
      $rules['specify_reason'] = 'required|string|max:255';
    }

    $request->validate($rules, [
      'specify_reason.required' => 'Please specify the reason when "Others: specify" is selected.',
    ]);

    // Find the application by its ID
    $application = GoodMoralApplication::findOrFail($id);
    $registrar = auth()->user();

    // Prepare the rejection reason
    $rejectionReason = $request->rejection_reason;
    if ($request->rejection_reason === 'Others: specify' && $request->specify_reason) {
      $rejectionReason = 'Others: ' . $request->specify_reason;
    }

    // Update the application status to 'rejected' with reason
    $application->status = 'rejected';
    $application->application_status = 'Rejected By Registrar ' . $registrar->fullname;
    $application->rejection_reason = $rejectionReason;
    $application->rejection_details = $request->rejection_details;
    $application->rejected_by = 'Registrar: ' . $registrar->fullname;
    $application->rejected_at = now();
    $application->action_history = ($application->action_history ?? '') . "\n" . now()->format('Y-m-d H:i:s') . " - Rejected by Registrar: " . $registrar->fullname . " (Reason: " . $rejectionReason . ")";
    $application->save();

    // Create notification for student
    $this->notifService->createFromApplication($application, '-1', 'Rejected: ' . $rejectionReason);

    // Redirect back with success message
    return redirect()->route('registrar.goodMoralApplication')->with('status', 'Application rejected successfully.');
  }

  /**
   * Reconsider a rejected application.
   */
  public function reconsider(Request $request, $id)
  {
    $request->validate([
      'reconsider_notes' => 'nullable|string|max:1000',
    ]);

    // Find the application by its ID
    $application = GoodMoralApplication::findOrFail($id);
    $registrar = auth()->user();

    // Update the application status back to 'pending'
    $application->status = 'pending';
    $application->application_status = null; // Reset application status
    $application->action_history = ($application->action_history ?? '') . "\n" . now()->format('Y-m-d H:i:s') . " - Reconsidered by Registrar: " . $registrar->fullname . ($request->reconsider_notes ? " (Notes: " . $request->reconsider_notes . ")" : "");
    $application->save();

    // Update notification status back to pending
    $notification = NotifArchive::where('reference_number', $application->reference_number)->first();
    if ($notification) {
      $notification->status = '0'; // Back to pending
      $notification->application_status = 'Reconsidered by Registrar';
      $notification->save();
    }

    // Redirect back with success message
    return redirect()->route('registrar.goodMoralApplication')->with('status', 'Application reconsidered successfully.');
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
