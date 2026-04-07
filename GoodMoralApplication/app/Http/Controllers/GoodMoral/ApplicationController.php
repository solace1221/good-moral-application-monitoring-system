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
use App\Helpers\CourseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use App\Traits\RoleCheck;

class ApplicationController extends Controller
{
  /**
   * Handle the application for a Good Moral Certificate.
   */
  use RoleCheck;

  public function __construct()
  {
    // Temporarily disable role check to fix authentication
    // $this->checkRole(['student', 'alumni']);
  }

  public function dashboard()
  {
    // Check if user is student or alumni
    if (!in_array(auth()->user()->account_type, ['student', 'alumni'])) {
      abort(403, 'Unauthorized access.');
    }

    $user = Auth::user();

    // Get the student record tied to this user by email (not ID)
    $student = RoleAccount::where('email', $user->email)->first();

    if (!$student) {
      return redirect()->back()->with('error', 'Student record not found.');
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
  public function applyForGoodMoralCertificate(Request $request)
  {
    $accountType = Auth::user()?->account_type;

    $prefix = 'REF'; // You can customize the prefix
    $timestamp = time(); // Current timestamp
    $randomString = Str::upper(Str::random(6)); // Random 6-character string
    $referenceNumber = $prefix . '-' . $timestamp . '-' . $randomString;

    // Validate the input
    $request->validate([
      'num_copies' => ['required', 'string', 'max:255'],
      'reason' => ['required', 'array', 'min:1'],
      'reason.*' => ['required', 'string', 'max:255'],
      'reason_other' => ['nullable', 'string', 'max:255'],
      'is_undergraduate' => ['nullable', 'in:yes,no'],
      'certificate_type' => ['required', 'in:good_moral,residency'],
    ]);

    // Get all valid course codes for validation
    $validCourses = array_keys(CourseHelper::getAllCourses());

    // Define valid semester options
    $validSemesters = [
      'First Semester',
      'Second Semester',
      'Summer Term'
    ];

    // Validate additional fields if the student is undergraduate
    if ($accountType === 'student') {
      $request->validate([
        'last_course_year_level' => ['nullable', 'string', 'in:' . implode(',', $validCourses)],
        'last_semester' => ['required', 'string', 'in:' . implode(',', $validSemesters)],
        'last_school_year' => ['required', 'string', 'regex:/^\d{4}-\d{4}$/'],
      ]);
    } else if ($accountType === 'alumni') {
      $request->validate([
        'course_completed' => ['nullable', 'string', 'in:' . implode(',', $validCourses)],
        'graduation_date' => ['required', 'date'],
      ]);
    } else {
      $request->validate([
        'last_course_year_level' => ['nullable', 'string', 'in:' . implode(',', $validCourses)],
        'last_semester' => ['nullable', 'string', 'in:' . implode(',', $validSemesters)],
        'last_school_year' => ['nullable', 'string', 'regex:/^\d{4}-\d{4}$/'],
        'course_completed' => ['nullable', 'string', 'in:' . implode(',', $validCourses)],
        'graduation_date' => ['nullable', 'date'],
      ]);
    }

    // Get the student details from the authenticated user
    $user = Auth::user();

    // Get the student record from role_account table using email
    $roleAccount = RoleAccount::where('email', $user->email)->first();

    if (!$roleAccount) {
      return redirect()->back()->with('error', 'Student record not found. Please contact the administrator.');
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
    GoodMoralApplication::create([
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

    NotifArchive::create([
      'number_of_copies' => $request->num_copies,
      'reference_number' => $referenceNumber,
      'fullname' => $fullname,
      'gender' => $userGender, // Add gender field
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
      'status' => '0',
    ]);

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
      return redirect()->back()->with('error', 'Student record not found.');
    }

    // Fetch notifications for the authenticated user using the student_id
    $notifications = NotifArchive::where('student_id', $roleAccount->student_id)
      ->orderBy('created_at', 'desc') // Optional: Order by latest notifications first
      ->get();

    $receipts = Receipt::whereIn('reference_num', $notifications->pluck('reference_number'))
      ->get()
      ->keyBy('reference_num');

    // Fetch detailed rejection information from GoodMoralApplication table
    $rejectionDetails = [];
    foreach ($notifications as $notification) {
      if (in_array($notification->status, ['-1', '-2', '-3'])) { // Rejected statuses
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

    // Return the view with the notifications and rejection details
    return view('notification', compact('notifications', 'receipts', 'rejectionDetails'));
  }

  public function notificationViolation()
  {
    // Get student record from role_account table
    $user = Auth::user();
    $roleAccount = RoleAccount::where('email', $user->email)->first();

    if (!$roleAccount) {
      return redirect()->back()->with('error', 'Student record not found.');
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
  public function upload(Request $request)
  {
    $request->validate([
      'reference_num' => 'required|string',
      'official_receipt_no' => 'required|string|max:255',
      'date_paid' => 'required|date|before_or_equal:today',
      'document_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

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
      \Log::error('Receipt validation failed with exception: ' . $e->getMessage(), [
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

      \Log::warning('Proceeding with receipt upload after basic validation');
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

    // Find the application to get student details
    $application = GoodMoralApplication::where('reference_number', $request->reference_num)->first();

    if ($application) {
      // Update application status to Ready for Moderator Print when receipt is uploaded
      if ($application->application_status === 'Approved by Administrator') {
        $application->application_status = 'Ready for Moderator Print';
        $application->save();
      }

      // Create notification for student - receipt uploaded, ready for printing
      NotifArchive::create([
        'number_of_copies' => $application->number_of_copies,
        'reference_number' => $application->reference_number,
        'fullname' => $application->fullname,
        'gender' => $application->gender,
        'reason' => $application->reason,
        'student_id' => $application->student_id,
        'department' => $application->department,
        'course_completed' => $application->course_completed,
        'graduation_date' => $application->graduation_date,
        'application_status' => null,
        'is_undergraduate' => $application->is_undergraduate,
        'last_course_year_level' => $application->last_course_year_level,
        'last_semester_sy' => $application->last_semester_sy,
        'certificate_type' => $application->certificate_type,
        'status' => '4', // Status 4 = Receipt uploaded, ready for printing
      ]);
    }

    return back()->with('status', 'Official receipt uploaded successfully! Your application is now ready for certificate printing.');
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
        \Log::warning('File uploaded without receipt-related keywords in filename', [
          'filename' => $originalName
        ]);
        // Don't reject, but log for monitoring
      }

      return [
        'is_valid' => true,
        'validation_type' => 'basic'
      ];

    } catch (\Exception $e) {
      \Log::error('Basic receipt validation failed: ' . $e->getMessage());
      return [
        'is_valid' => false,
        'error_message' => 'Error validating the uploaded file. Please try uploading a different format.'
      ];
    }
  }

  /**
   * Serve storage files with proper headers
   */
  public function serveFile($path)
  {
    $file = storage_path('app/public/' . $path);

    if (!file_exists($file)) {
      abort(404, 'File not found');
    }

    // Check if file is readable
    if (!is_readable($file)) {
      abort(403, 'File access forbidden');
    }

    // Get the file's MIME type
    $mimeType = mime_content_type($file) ?: 'application/octet-stream';

    // Return the file with proper headers
    return response()->file($file, [
      'Content-Type' => $mimeType,
      'Cache-Control' => 'public, max-age=3600',
      'X-Content-Type-Options' => 'nosniff',
    ]);
  }

  /**
   * Show the student profile page
   */
  public function profile()
  {
    // Check if user is student or alumni
    if (!in_array(auth()->user()->account_type, ['student', 'alumni'])) {
      abort(403, 'Unauthorized access.');
    }

    $user = Auth::user();

    // Get the student record tied to this user (use email, not id)
    $student = RoleAccount::where('email', $user->email)->first();

    if (!$student) {
      return redirect()->back()->with('error', 'Student record not found.');
    }

    return view('student.profile', compact('student'));
  }

  /**
   * Update the student's password
   */
  public function updatePassword(Request $request)
  {
    // Check if user is student or alumni
    if (!in_array(auth()->user()->account_type, ['student', 'alumni'])) {
      abort(403, 'Unauthorized access.');
    }

    $request->validate([
      'current_password' => ['required', 'current_password'],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = Auth::user();
    $oldEmail = $user->email;

    // Update the password
    $user->update([
      'password' => Hash::make($request->password),
    ]);

    return redirect()->route('student.profile')->with('status', 'Password updated successfully!');
  }

  /**
   * Update the student's email address
   */
  public function updateEmail(Request $request)
  {
    // Check if user is student or alumni
    if (!in_array(auth()->user()->account_type, ['student', 'alumni'])) {
      abort(403, 'Unauthorized access.');
    }

    $request->validate([
      'email' => ['required', 'email', 'max:255', 'unique:role_account,email,' . auth()->id()],
    ]);

    $user = Auth::user();
    $oldEmail = $user->email;

    // Update the email
    $user->update([
      'email' => $request->email,
    ]);

    return redirect()->route('student.profile')->with('status', 'Email updated successfully!');
  }

  /**
   * Update the student's profile information
   */
  public function updateProfile(Request $request)
  {
    // Check if user is student, alumni, or PSG officer
    if (!in_array(auth()->user()->account_type, ['student', 'alumni', 'psg_officer'])) {
      abort(403, 'Unauthorized access.');
    }

    $user = Auth::user();

    // Base validation rules - RESTRICTED: Name fields, course, and year_level are excluded for students/alumni
    if (in_array($user->account_type, ['student', 'alumni'])) {
      // Students and alumni cannot update name fields, course, or year_level
      $rules = [
        'email' => ['required', 'email', 'max:255', 'unique:role_account,email,' . auth()->id()],
        'gender' => ['required', 'string', 'in:male,female'],
        // Removed name fields, course, and year_level for security
      ];

      // Validate only editable fields
      $request->validate($rules);

      // Only update allowed fields for students/alumni
      $updateData = [
        'email' => $request->email,
        'gender' => $request->gender,
      ];

      // Log attempted unauthorized changes for security monitoring
      $restrictedFields = ['first_name', 'middle_name', 'last_name', 'extension', 'course', 'year_level'];
      $attemptedChanges = array_intersect(array_keys($request->all()), $restrictedFields);
      
      if (!empty($attemptedChanges)) {
        \Log::warning('Unauthorized profile update attempt', [
          'user_id' => $user->id,
          'student_id' => $user->student_id,
          'attempted_fields' => $attemptedChanges,
          'ip_address' => $request->ip(),
        ]);
      }

      $oldEmail = $user->email;
      $user->update($updateData);
      
      // Sync profile update to Clearance System (students/alumni)
      
      $successMessage = 'Profile updated successfully! Note: Name changes require formal request to Registrar/OSA. Academic information is managed by the Registrar.';
      
    } else if ($user->account_type === 'psg_officer') {
      // PSG officers can update more fields including name and organizational info
      $rules = [
        'first_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
        'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z\s]*$/'],
        'last_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
        'extension' => ['nullable', 'string', 'max:10', 'regex:/^[A-Za-z\s]*$/'],
        'email' => ['required', 'email', 'max:255', 'unique:role_account,email,' . auth()->id()],
        'gender' => ['required', 'string', 'in:male,female'],
        'organization' => ['required', 'string', 'max:255'],
        'position' => ['required', 'string', 'max:255'],
      ];

      $request->validate($rules);

      // Create fullname from parts for PSG officers
      $fullname = $request->last_name . ', ' . $request->first_name;
      if ($request->middle_name) {
        $fullname .= ' ' . $request->middle_name;
      }

      $updateData = [
        'fullname' => $fullname,
        'mname' => $request->middle_name,
        'extension' => $request->extension,
        'email' => $request->email,
        'gender' => $request->gender,
        'organization' => $request->organization,
        'position' => $request->position,
      ];

      $oldEmail = $user->email;
      $user->update($updateData);
      
      $successMessage = 'Profile updated successfully!';
    }

    return redirect()->route('student.profile')->with('status', $successMessage);
  }
}

