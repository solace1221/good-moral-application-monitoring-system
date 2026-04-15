<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeanApplication;
use App\Models\SecOSAApplication;
use App\Models\GoodMoralApplication;
use App\Models\NotifArchive;
use App\Models\StudentViolation;
use App\Models\ViolationNotif;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\RoleCheck;
use Illuminate\Support\Str;
use App\Services\GoodMoralWorkflowService;
use App\Services\NotificationArchiveService;
use App\Services\ViolationService;
use App\Http\Requests\DeanRejectRequest;
use App\Http\Requests\ReconsiderApplicationRequest;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
  use RoleCheck;

  protected GoodMoralWorkflowService $workflowService;
  protected NotificationArchiveService $notifService;

  public function __construct(
    GoodMoralWorkflowService $workflowService,
    NotificationArchiveService $notifService
  ) {
    $this->workflowService = $workflowService;
    $this->notifService = $notifService;
  }

  public function application()
  {
    try {
      // Access the authenticated dean
      $dean = Auth::user();

      // Validate dean authentication
      if (!$dean) {
        Log::error('Dean Application Access: No authenticated user');
        return redirect()->route('login')->with('error', 'Please login to access applications.');
      }

      if (!in_array($dean->account_type, ['dean'])) {
        Log::error('Dean Application Access: User is not a dean', ['user_type' => $dean->account_type]);
        return redirect()->route('dashboard')->with('error', 'Access denied. Dean privileges required.');
      }

      // TODO: Legacy method - review for removal later
      // Fetch pending applications assigned to the dean's department from DeanApplication (legacy)
      $legacyApplications = DeanApplication::where('status', 'pending')
        ->where('department', $dean->department) // Filtering by department
        ->with('student') // Eager load the related student data
        ->get();

      // Fetch Good Moral Applications that need dean approval
      $goodMoralApplications = GoodMoralApplication::approvedByRegistrar()
        ->where('department', $dean->department)
        ->where('certificate_type', 'good_moral')
        ->whereNotNull('application_status')
        ->orderBy('updated_at', 'desc')
        ->get();

      // Fetch Residency Applications that need dean approval
      $residencyApplications = GoodMoralApplication::approvedByRegistrar()
        ->where('department', $dean->department)
        ->where('certificate_type', 'residency')
        ->whereNotNull('application_status')
        ->orderBy('updated_at', 'desc')
        ->get();

      // Combine all applications for total count
      $allApplications = $goodMoralApplications->merge($residencyApplications);

      // Organize applications by type
      $applications = [
        'legacy' => $legacyApplications,
        'good_moral' => $goodMoralApplications,
        'residency' => $residencyApplications,
        'all_new' => $allApplications
      ];

      return view('dean.application', [
        'applications' => $applications,
        'department' => $dean->department, // pass department to view
      ]);

    } catch (\Exception $e) {
      Log::error('Dean Application Error', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'user_id' => Auth::id()
      ]);

      return redirect()->route('dean.dashboard')->with('error', 'Unable to load applications. Please try again.');
    }
  }

  /**
   * Approve a Dean application.
   *
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  // TODO: Legacy method - review for removal later
  public function approve($id)
  {
    try {
      // Retrieve the application
      $application = DeanApplication::findOrFail($id);
      $dean = Auth::user();

      // Check if the logged-in dean has permission to approve the application
      if ($application->department !== $dean->department) {
        return redirect()->route('dean.dashboard')->with('error', 'Unauthorized access to application.');
      }

      // Prevent approving already approved applications
      if ($application->status == 'approved') {
        return redirect()->route('dean.dashboard')->with('error', 'This application has already been approved.');
      }

      // Update the application status to 'approved'
      $application->status = 'approved';
      $application->save();

      // Create SecOSA application if it doesn't already exist
      $student = $application->student;
      if (!$student) {
        return redirect()->route('dean.dashboard')->with('error', 'Student not found.');
      }

      // Prevent creating duplicate SecOSAApplication
      if (SecOSAApplication::where('student_id', $student->student_id)->exists()) {
        return redirect()->route('dean.dashboard')->with('error', 'SecOSA application already exists for this student.');
      }
      
      $student_id = $application->student_id;

      DB::transaction(function () use ($application, $dean, $student, $student_id) {
        // Retrieve the GoodMoralApplication for the same student
        $goodMoralApplication = GoodMoralApplication::where('student_id', $student_id)->first();
        if ($goodMoralApplication) {
          // Update to waiting_for_payment - do NOT create HeadOSAApplication yet
          $goodMoralApplication->status = 'waiting_for_payment';
          $goodMoralApplication->application_status = 'Approved by Dean:' . $dean->fullname . ' - Waiting for Payment';
          $goodMoralApplication->save();

          // Generate payment notice so student can pay at Business Affairs
          $receiptService = new \App\Services\ReceiptService();
          $receiptService->generatePaymentNotice($goodMoralApplication);

          $this->notifService->createFromApplication($goodMoralApplication, '3');
        }
      });
      // Note: Notification will be created by the new system (approveGoodMoral method)
      // when the GoodMoralApplication is processed

      return back()->with('status', 'Application approved! Student has been notified to pay at Business Affairs and upload their receipt.');
    } catch (\Exception $e) {
      return redirect()->route('dean.dashboard')->with('error', 'Error processing approval: ' . $e->getMessage());
    }
  }

  /**
   * Reject a Dean application.
   *
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  // TODO: Legacy method - review for removal later
  public function reject($id)
  {
    // Retrieve the application
    $application = DeanApplication::findOrFail($id);

    // Check if the logged-in dean has permission to reject the application
    $this->authorizeApplication($application);

    // Prevent rejecting already rejected applications
    if ($application->status == 'rejected') {
      return redirect()->route('dean.dashboard')->with('error', 'This application has already been rejected.');
    }
    $dean = Auth::user();
    $student_id = $application->student_id;

    DB::transaction(function () use ($application, $dean, $student_id) {
      // Retrieve the GoodMoralApplication for the same student
      $goodMoralApplication = GoodMoralApplication::where('student_id', $student_id)->first();
      if ($goodMoralApplication) {
        // Update the application status for GoodMoralApplication
        $goodMoralApplication->application_status = 'Rejected by Dean:' . $dean->fullname;
        $goodMoralApplication->save();
      }

      // Update the application status to 'rejected'
      $application->status = 'rejected';
      $application->save();

      if ($goodMoralApplication) {
        $this->notifService->createFromApplication($goodMoralApplication, '-3');
      }
    });

    return redirect()->route('dean.dashboard')->with('status', 'Application rejected!');
  }

  /**
   * Approve a Good Moral Application.
   */
  public function approveGoodMoral($id)
  {
    // Get authenticated dean user
    $dean = Auth::user();
    if (!$dean) {
      return redirect()->route('login')->with('error', 'Authentication error');
    }
    
    try {
      $application = GoodMoralApplication::findOrFail($id);
      
      // Check if application belongs to dean's department
      if ($application->department !== $dean->department) {
        return redirect()->route('dean.application')->with('error', 'Unauthorized access to application.');
      }

      // Check if application is in correct status
      if (!str_contains($application->application_status, 'Approved By Registrar') && 
          !str_contains($application->application_status, 'Approved by Registrar')) {
        return redirect()->route('dean.application')->with('error', 'Application is not ready for dean approval.');
      }

      // Update application status and create notification via service
      $result = $this->workflowService->approveByDean($application, $dean->fullname);

      $successMessage = "Good Moral application approved! Payment notice generated. Student has been notified to pay at Business Affairs and upload their receipt.";
      
      // Check if the request is AJAX/XHR
      if (request()->ajax() || request()->wantsJson()) {
        return response()->json([
          'success' => true, 
          'message' => $successMessage
        ]);
      }
      
      // Regular response for non-AJAX requests
      return redirect()->route('dean.application')->with('status', $successMessage);
    } catch (\Exception $e) {
      if (request()->ajax() || request()->wantsJson()) {
        return response()->json([
          'success' => false,
          'error' => $e->getMessage()
        ], 500);
      }
      
      return redirect()->route('dean.application')->with('error', 'Error approving application: ' . $e->getMessage());
    }
  }

  /**
   * Reject a Good Moral Application.
   */
  public function rejectGoodMoral($id)
  {
    $dean = Auth::user();
    $application = GoodMoralApplication::findOrFail($id);

    // Check if application belongs to dean's department
    if ($application->department !== $dean->department) {
      return redirect()->route('dean.application')->with('error', 'Unauthorized access to application.');
    }

    // Check if application is in correct status
    if (!str_contains($application->application_status, 'Approved By Registrar') &&
        !str_contains($application->application_status, 'Approved by Registrar')) {
      return redirect()->route('dean.application')->with('error', 'Application is not ready for dean action.');
    }

    // Update application status and create notification via service
    $this->workflowService->rejectByDean($application, $dean->fullname);

    return redirect()->route('dean.application')->with('status', 'Good Moral application rejected successfully!');
  }

  /**
   * Reject application with detailed reason.
   */
  // TODO: Legacy method - review for removal later (no route defined)
  public function rejectWithReason(DeanRejectRequest $request, $id)
  {

    $dean = Auth::user();
    $application = GoodMoralApplication::findOrFail($id);

    // Check if application belongs to dean's department
    if ($application->department !== $dean->department) {
      return redirect()->route('dean.application')->with('error', 'Unauthorized access to application.');
    }

    // Update application with rejection details and create notification via service
    $this->workflowService->rejectByDean($application, $dean->fullname, $request->rejection_reason, $request->rejection_details);

    return redirect()->route('dean.application')->with('status', 'Application rejected successfully.');
  }

  /**
   * Reconsider a rejected application.
   */
  public function reconsider(ReconsiderApplicationRequest $request, $id)
  {

    $dean = Auth::user();
    $application = GoodMoralApplication::findOrFail($id);

    // Check if application belongs to dean's department
    if ($application->department !== $dean->department) {
      return redirect()->route('dean.application')->with('error', 'Unauthorized access to application.');
    }

    // Reset application status
    DB::transaction(function () use ($application, $dean, $request) {
      $application->status = 'pending';
      $registrarName = '';
      if (str_contains($application->application_status, 'Approved By Registrar')) {
        $registrarName = explode('Approved By Registrar', $application->application_status)[1] ?? '';
      } elseif (str_contains($application->application_status, 'Approved by Registrar')) {
        $registrarName = explode('Approved by Registrar', $application->application_status)[1] ?? '';
      }
      $application->application_status = 'Approved by Registrar' . $registrarName;
      $application->action_history = ($application->action_history ?? '') . "\n" . now()->format('Y-m-d H:i:s') . " - Reconsidered by Dean: {$dean->fullname}" . ($request->reconsider_notes ? " (Notes: {$request->reconsider_notes})" : "");
      $application->save();

      // Update notification
      $notification = NotifArchive::where('reference_number', $application->reference_number)->first();
      if ($notification) {
        $notification->status = '1'; // Back to dean approval
        $notification->application_status = 'Reconsidered by Dean';
        $notification->save();
      }
    });

    return redirect()->route('dean.application')->with('status', 'Application reconsidered successfully.');
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

  /**
   * Authorize that the logged-in dean can approve/reject the application.
   *
   * @param  \App\Models\DeanApplication  $application
   * @return void
   */
  protected function authorizeApplication($application)
  {
    $dean = Auth::user();

    // Check if the application belongs to the logged-in dean's department
    if ($application->department !== $dean->department) {
      abort(403, 'Unauthorized access to application.');
    }
  }

  public function deanviolationapprove($id)
  {
    $userDepartment = Auth::user()->department;

    $violation = StudentViolation::findOrFail($id);

    // For minor violations, approve and send to Admin for final approval
    if ($violation->offense_type === 'minor') {
      $violation->status = "1"; // Mark as Dean approved, pending Admin approval
      $violation->save();

      ViolationNotif::create([
        'ref_num' => 'DEAN-APPROVED',
        'student_id' => $violation->student_id,
        'status' => 0,  // pending status
        'notif' => "Your minor violation has been approved by the Dean ({$userDepartment}). The case is now pending Admin final approval. Please wait for further instructions.",
      ]);

      return back()->with('success', "Minor violation approved by Dean! Sent to Admin for final approval.");
    } else {
      // For major violations, generate case number
      $caseNumber = ViolationService::generateCaseNumber();
      $violation->ref_num = $caseNumber;
      $violation->status = "1";

      $violation->save();
      // Get the violation details to find article reference
      $violationRecord = \App\Models\Violation::where('description', $violation->violation)->first();
      $article = $violationRecord ? $violationRecord->article : null;

      // Create notification using new format
      $proceedingsMessage = generateHandbookReference($violation->offense_type, $article) . ". Your violation proceedings have been approved by the Dean with case number: {$caseNumber}. Please proceed to the Administrator for final resolution.";

      ViolationNotif::create([
        'ref_num' => $caseNumber,
        'student_id' => $violation->student_id,
        'status' => 0,  // initial status
        'notif' => $proceedingsMessage,
      ]);

      return back()->with('success', "Approve the proceedings with Case number: {$caseNumber}");
    }
  }

  public function deanviolationdecline($id)
  {
    $userDepartment = Auth::user()->department;

    $violation = StudentViolation::findOrFail($id);

    // Delete the violation record — dean has declined it
    $studentId = $violation->student_id;
    $violation->delete();

    ViolationNotif::create([
      'ref_num'    => 'DEAN-DECLINED',
      'student_id' => $studentId,
      'status'     => 0,
      'notif'      => "Your minor violation has been declined by the Dean ({$userDepartment}). The record has been removed.",
    ]);

    return back()->with('success', 'Minor violation has been declined and removed.');
  }
}
