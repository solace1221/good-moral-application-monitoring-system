<?php

namespace App\Http\Controllers\GoodMoral;
use App\Http\Controllers\Controller;

use App\Models\ViolationNotif;
use App\Models\Receipt;
use Illuminate\Support\Str;
use App\Models\GoodMoralApplication;
use App\Models\RoleAccount;
use App\Models\NotifArchive;
use App\Models\StudentViolation;
use App\Services\ReceiptValidationService;
use App\Services\NotificationArchiveService;
use App\Helpers\CourseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\RoleCheck;
use App\Http\Requests\ApplyGoodMoralRequest;
use App\Http\Requests\UploadReceiptRequest;

class ApplicationController extends Controller
{
  /**
   * Handle the application for a Good Moral Certificate.
   */
  use RoleCheck;

  protected NotificationArchiveService $notifService;

  public function __construct(NotificationArchiveService $notifService)
  {
    $this->notifService = $notifService;
  }

  public function dashboard()
  {
    $accountType = Auth::user()->account_type;

    // Redirect non-student/alumni users to their correct dashboard
    if (!in_array($accountType, ['student', 'alumni'])) {
      return redirect(match ($accountType) {
        'admin' => route('admin.dashboard'),
        'dean' => route('dean.dashboard'),
        'sec_osa' => route('sec_osa.dashboard'),
        'psg_officer' => route('PsgOfficer.dashboard'),
        'registrar' => route('registrar.goodMoralApplication'),
        'prog_coor' => route('prog_coor.major'),
        default => route('login'),
      });
    }

    $user = Auth::user();

    // Get the student record tied to this user by email (not ID)
    $student = RoleAccount::where('email', $user->email)->first();

    if (!$student) {
      Auth::logout();
      return redirect()->route('login')->with('error', 'Student record not found. Please contact the administrator.');
    }

    $studentId = $student->student_id;
    $fullname = $student->fullname;
    $Violation = StudentViolation::where('student_id', $studentId)
      ->where('status', '!=', 2)
      ->get(); // fetches a collection

    // Determine available certificate types based on account type and violations
    $availableCertificates = $this->getAvailableCertificates($user->account_type, $Violation);

    // Get student's course and year level from their profile (static, not changeable)
    $studentCourse = $student->course;
    $studentCourseName = $studentCourse ? CourseHelper::getCourseName($studentCourse) : null;
    $studentYearLevel = $student->year_level;

    return view('dashboard', compact('Violation', 'fullname', 'availableCertificates', 'studentCourse', 'studentCourseName', 'studentYearLevel'));
  }

  /**
   * Determine which certificate types are available for the user
   */
  private function getAvailableCertificates($accountType, $violations)
  {
    $certificates = [];

    if ($accountType === 'student') {
      // Students with no violations can apply for Good Moral Certificate
      if ($violations->isEmpty()) {
        $certificates[] = [
          'type' => 'good_moral',
          'name' => 'Good Moral Certificate',
          'description' => 'Certificate of good moral character for students'
        ];
      } else {
        // Students with violations can apply for Certificate of Residency
        $certificates[] = [
          'type' => 'residency',
          'name' => 'Certificate of Residency',
          'description' => 'Certificate confirming residency/attendance at the institution'
        ];
      }
    } elseif ($accountType === 'alumni') {
      if ($violations->isEmpty()) {
        // Alumni with no violations can apply for Good Moral Certificate
        $certificates[] = [
          'type' => 'good_moral',
          'name' => 'Good Moral Certificate',
          'description' => 'Certificate of good moral character for alumni'
        ];
      } else {
        // Alumni with violations can apply for Certificate of Residency
        $certificates[] = [
          'type' => 'residency',
          'name' => 'Certificate of Residency',
          'description' => 'Certificate confirming residency/attendance at the institution'
        ];
      }
    }

    return $certificates;
  }
  public function applyForGoodMoralCertificate(ApplyGoodMoralRequest $request)
  {
    Log::info('Good Moral Application submitted', [
      'user_id' => Auth::id(),
      'inputs' => $request->except(['_token']),
    ]);

    $accountType = Auth::user()?->account_type;

    $prefix = 'REF'; // You can customize the prefix
    $timestamp = time(); // Current timestamp
    $randomString = Str::upper(Str::random(6)); // Random 6-character string
    $referenceNumber = $prefix . '-' . $timestamp . '-' . $randomString;

    // Get all valid course codes for validation
    $validCourses = array_keys(CourseHelper::getAllCourses());

    // Define valid semester options
    $validSemesters = [
      'First Semester',
      'Second Semester',
      'Summer Term'
    ];

    // Get the student details from the authenticated user
    $user = Auth::user();

    // Get the student record from role_account table using email
    $roleAccount = RoleAccount::where('email', $user->email)->first();

    if (!$roleAccount) {
      Auth::logout();
      return redirect()->route('login')->with('error', 'Student record not found. Please contact the administrator.');
    }

    $studentId = $roleAccount->student_id;
    $fullname = $roleAccount->fullname;
    $studentDepartment = $roleAccount->department;

    // Process multiple reasons
    $reasons = $request->reason; // This is now an array

    // If "Others" is selected and custom reason is provided, replace "Others" with the custom reason
    if (in_array('Others', $reasons) && $request->reason_other) {
      $reasons = array_map(function($reason) use ($request) {
        return $reason === 'Others' ? $request->reason_other : $reason;
      }, $reasons);
    }

    // Remove "Others" if no custom reason was provided
    if (in_array('Others', $reasons) && !$request->reason_other) {
      $reasons = array_filter($reasons, function($reason) {
        return $reason !== 'Others';
      });
    }

    $selectedReason = $reasons; // Store as array

    // Get gender from user profile
    $userGender = Auth::user()->gender ?? 'male'; // Default to male if not set

    // Combine semester and school year
    $lastSemesterSy = null;
    if ($request->last_semester && $request->last_school_year) {
      $lastSemesterSy = $request->last_semester . ' of ' . $request->last_school_year;
    }

    // Save the application in the database
    $application = GoodMoralApplication::create([
      'number_of_copies' => $request->num_copies,
      'reference_number' => $referenceNumber,
      'fullname' => $fullname,
      'gender' => $userGender, // Get gender from user profile
      'reason' => $selectedReason,
      'student_id' => $studentId,
      'department' => $studentDepartment,
      'course_completed' => $request->course_completed ?? null, // Allowing this to be null
      'graduation_date' => $request->graduation_date ?? null,
      'application_status' => null,
      'is_undergraduate' => $request->is_undergraduate === 'yes',
      'last_course_year_level' => $request->last_course_year_level ?? null,
      'last_semester_sy' => $lastSemesterSy,
      'certificate_type' => $request->certificate_type, // Add certificate type
      'status' => 'pending',
    ]);

    $this->notifService->createFromApplication($application, '0');

    // Redirect to the dashboard with a success message
    $certificateName = $request->certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency';
    return redirect()->route('dashboard')->with('status', "Application for {$certificateName} submitted successfully!");
  }
  public function notification()
  {
    // Get student record from role_account table
    $user = Auth::user();
    $roleAccount = RoleAccount::where('email', $user->email)->first();

    if (!$roleAccount) {
      Auth::logout();
      return redirect()->route('login')->with('error', 'Student record not found. Please contact the administrator.');
    }

    // Fetch all notifications for this student, ordered desc so latest is first per group
    $notifications = NotifArchive::where('student_id', $roleAccount->student_id)
      ->orderBy('created_at', 'desc')
      ->get();

    // Group by reference_number so the view renders one row per application
    $grouped = $notifications->groupBy('reference_number');

    $receipts = Receipt::whereIn('reference_num', $notifications->pluck('reference_number'))
      ->get()
      ->keyBy('reference_num');

    // Fetch detailed rejection information from GoodMoralApplication table
    $rejectionDetails = [];
    foreach ($notifications as $notification) {
      if (in_array($notification->status, ['-1', '-2', '-3'])) {
        $application = GoodMoralApplication::where('reference_number', $notification->reference_number)->first();
        if ($application && $application->rejection_reason) {
          $rejectionDetails[$notification->reference_number] = [
            'rejection_reason' => $application->rejection_reason,
            'rejection_details' => $application->rejection_details,
            'rejected_by' => $application->rejected_by,
            'rejected_at' => $application->rejected_at,
          ];
        }
      }
    }

    return view('notification', compact('grouped', 'receipts', 'rejectionDetails'));
  }

  public function notificationViolation()
  {
    // Get student record from role_account table
    $user = Auth::user();
    $roleAccount = RoleAccount::where('email', $user->email)->first();

    if (!$roleAccount) {
      Auth::logout();
      return redirect()->route('login')->with('error', 'Student record not found. Please contact the administrator.');
    }

    $studentId = $roleAccount->student_id;

    // Fetch notifications for the authenticated user using the student_id
    $notifications = ViolationNotif::where('student_id', $studentId)
      ->orderBy('created_at', 'desc') // Optional: Order by latest notifications first
      ->get();

    // Mark all violation notifications as read when viewed
    ViolationNotif::where('student_id', $studentId)
      ->where('status', 0)
      ->update(['status' => 1]);

    // Return the view with the notifications
    return view('notificationViolation', compact('notifications'));
  }

  /**
   * Get notification counts for student sidebar
   */
  public function getNotificationCounts()
  {
    // Get student record from role_account table
    $user = Auth::user();
    $roleAccount = RoleAccount::where('email', $user->email)->first();

    if (!$roleAccount) {
      return response()->json([
        'applicationNotifications' => 0,
        'violationNotifications' => 0,
        'totalNotifications' => 0,
        'error' => 'Student record not found'
      ]);
    }

    $studentId = $roleAccount->student_id;

    // Count application notifications by status (each step creates a new notification)
    $applicationNotifications = NotifArchive::where('student_id', $studentId)
      ->whereIn('status', ['0', '1', '2', '3', '4', '5', '-1', '-2', '-3']) // All valid statuses
      ->count();

    // Count unread violation notifications (status 0 = unread for violations)
    $violationNotifications = ViolationNotif::where('student_id', $studentId)
      ->where('status', 0) // Only unread notifications
      ->count();

    // Get latest application status for debugging
    $latestNotification = NotifArchive::where('student_id', $studentId)
      ->orderBy('created_at', 'desc')
      ->first();

    return response()->json([
      'applicationNotifications' => $applicationNotifications,
      'violationNotifications' => $violationNotifications,
      'totalNotifications' => $applicationNotifications + $violationNotifications,
      'debug' => [
        'student_id' => $studentId,
        'latest_status' => $latestNotification ? $latestNotification->status : 'none',
        'latest_reference' => $latestNotification ? $latestNotification->reference_number : 'none',
      ]
    ]);
  }
  public function upload(UploadReceiptRequest $request)
  {

    // Validate that the uploaded file is actually a receipt using the service
    $uploadedFile = $request->file('document_path');

    try {
      $validationService = new ReceiptValidationService();
      $validationResult = $validationService->validateReceiptContent($uploadedFile);

      if (!$validationResult['is_valid']) {
        return back()->withErrors([
          'document_path' => $validationResult['error_message']
        ])->withInput();
      }
    } catch (\Exception $e) {
      // Log the error but still validate basic file properties
      Log::error('Receipt validation failed with exception: ' . $e->getMessage(), [
        'file' => $uploadedFile->getClientOriginalName(),
        'trace' => $e->getTraceAsString()
      ]);

      // Perform basic validation as fallback
      $basicValidation = $this->performBasicReceiptValidation($uploadedFile);
      if (!$basicValidation['is_valid']) {
        return back()->withErrors([
          'document_path' => $basicValidation['error_message']
        ])->withInput();
      }

      Log::warning('Proceeding with receipt upload after basic validation');
    }

    // Store the file
    $uploadedFile = $request->file('document_path');
    $path = $uploadedFile->store('uploaded_receipts', 'public');

    // Find existing receipt record (payment notice) and update it
    $receipt = Receipt::where('reference_num', $request->reference_num)->first();

    if ($receipt) {
      // Update existing payment notice with uploaded receipt
      $receipt->update([
        'official_receipt_no' => $request->official_receipt_no,
        'date_paid' => $request->date_paid,
        'document_path' => $path, // Replace payment notice with uploaded receipt
        'status' => 'uploaded', // Change status to uploaded
        'payment_method' => 'Cash/Bank Payment',
      ]);
    } else {
      // Create new receipt record if none exists
      Receipt::create([
        'reference_num' => $request->reference_num,
        'official_receipt_no' => $request->official_receipt_no,
        'date_paid' => $request->date_paid,
        'document_path' => $path,
        'status' => 'uploaded',
        'payment_method' => 'Cash/Bank Payment',
      ]);
    }

    // Find the application and delegate workflow to service
    $application = GoodMoralApplication::where('reference_number', $request->reference_num)->first();

    if ($application && str_contains($application->application_status, 'Waiting for Payment')) {
      // Get the receipt record we just created/updated
      $receipt = Receipt::where('reference_num', $request->reference_num)->first();

      // Delegate to workflow service
      $workflowService = app(\App\Services\GoodMoralWorkflowService::class);
      $workflowService->handleReceiptUpload($application, $receipt);
    }

    return back()->with('status', 'Official receipt uploaded successfully! Your application is now pending admin review.');
  }



  /**
   * Perform basic receipt validation as fallback when full validation fails
   */
  private function performBasicReceiptValidation($file)
  {
    try {
      $originalName = $file->getClientOriginalName();
      $mimeType = $file->getMimeType();
      $fileSize = $file->getSize();

      // Check for suspicious filenames
      $suspiciousPatterns = [
        'screenshot', 'screen_shot', 'screen-shot', 'camera', 'gallery',
        'download', 'whatsapp', 'facebook', 'instagram', 'twitter',
        'social', 'edited', 'copy', 'duplicate', 'snap', 'capture'
      ];

      $filenameLower = strtolower($originalName);
      foreach ($suspiciousPatterns as $pattern) {
        if (strpos($filenameLower, $pattern) !== false) {
          return [
            'is_valid' => false,
            'error_message' => 'The file name suggests this is not an original receipt. Please upload the official receipt document from Business Affairs Office.'
          ];
        }
      }

      // Check file size (receipts should be reasonable size)
      if ($fileSize < 10000) { // Less than 10KB is suspicious
        return [
          'is_valid' => false,
          'error_message' => 'The uploaded file is too small to be a valid receipt. Please upload a clear, complete receipt.'
        ];
      }

      if ($fileSize > 5000000) { // More than 5MB is suspicious
        return [
          'is_valid' => false,
          'error_message' => 'The uploaded file is too large. Please upload a properly sized receipt document.'
        ];
      }

      // Check MIME type
      $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
      if (!in_array($mimeType, $allowedMimeTypes)) {
        return [
          'is_valid' => false,
          'error_message' => 'Invalid file type. Please upload a PDF, JPG, JPEG, or PNG file.'
        ];
      }

      // Basic filename validation for receipts
      $receiptKeywords = ['receipt', 'or', 'official', 'payment', 'business', 'affairs'];
      $hasReceiptKeyword = false;
      foreach ($receiptKeywords as $keyword) {
        if (strpos($filenameLower, $keyword) !== false) {
          $hasReceiptKeyword = true;
          break;
        }
      }

      if (!$hasReceiptKeyword) {
        Log::warning('File uploaded without receipt-related keywords in filename', [
          'filename' => $originalName
        ]);
        // Don't reject, but log for monitoring
      }

      return [
        'is_valid' => true,
        'validation_type' => 'basic'
      ];

    } catch (\Exception $e) {
      Log::error('Basic receipt validation failed: ' . $e->getMessage());
      return [
        'is_valid' => false,
        'error_message' => 'Error validating the uploaded file. Please try uploading a different format.'
      ];
    }
  }
}
