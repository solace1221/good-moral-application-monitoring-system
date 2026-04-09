<?php

namespace App\Http\Controllers\GoodMoral;
use App\Http\Controllers\Controller;

use App\Models\ViolationNotif;
use App\Models\GoodMoralApplication;
use App\Models\SecOSAApplication;
use App\Models\NotifArchive;
use App\Models\RoleAccount;
use App\Models\StudentRegistration;
use App\Traits\RoleCheck;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentViolation;
use App\Models\Violation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\CertificateService;
use App\Services\DashboardStatsService;
use App\Services\ViolationService;
use App\Http\Requests\UploadProceedingsRequest;
use App\Http\Requests\UploadViolationDocumentRequest;
use App\Http\Requests\UpdateStaffProfileRequest;
use App\Http\Requests\UpdateModeratorEmailRequest;
use App\Http\Requests\UpdateModeratorPasswordRequest;

class SecOSAController extends Controller
{

  use RoleCheck;

  protected CertificateService $certificateService;
  protected DashboardStatsService $statsService;
  protected ViolationService $violationService;

  public function __construct(
    CertificateService $certificateService,
    DashboardStatsService $statsService,
    ViolationService $violationService
  ) {
    $this->certificateService = $certificateService;
    $this->statsService = $statsService;
    $this->violationService = $violationService;

    // Apply middleware to all methods except profile
    $action = request()->route() ? request()->route()->getActionMethod() : null;
    
    // Skip role check if accessing profile method
    if ($action !== 'profile') {
      $this->checkRole(['sec_osa']);
    }
  }
  public function dashboard()
  {
    // Set up frequency filter options
    $frequencyOptions = [
      'weekly' => 'Weekly',
      'monthly' => 'Monthly',
      'yearly' => 'Yearly',
      'all' => 'All Time'
    ];
    $frequency = request()->get('frequency', 'monthly');
    $frequencyLabel = $frequencyOptions[$frequency];

    // Use DashboardStatsService for stats
    $deptCounts = $this->statsService->getApplicationCountsByDepartment();
    $site = $deptCounts['SITE'];
    $saste = $deptCounts['SASTE'];
    $sbahm = $deptCounts['SBAHM'];
    $snahs = $deptCounts['SNAHS'];
    $som = $deptCounts['SOM'];
    $gradsch = $deptCounts['GRADSCH'];

    $vStats = $this->statsService->getViolationStats();
    $minorPending = $vStats['minorPending'];
    $minorResolved = $vStats['minorResolved'];
    $minorTotal = $vStats['minorTotal'];
    $minorResolvedPercentage = $vStats['minorResolvedPercentage'];
    $majorPending = $vStats['majorPending'];
    $majorResolved = $vStats['majorResolved'];
    $majorTotal = $vStats['majorTotal'];
    $majorResolvedPercentage = $vStats['majorResolvedPercentage'];

    $deptViolations = $this->statsService->getViolationsByDepartment();
    $majorViolationsByDept = $deptViolations['majorViolationsByDept'];
    $minorViolationsByDept = $deptViolations['minorViolationsByDept'];

    $applications = SecOSAApplication::where('status', 'pending')->get();

    // Fetch applications ready for printing and already printed (from admin approval with receipt uploaded)
    $printApplications = GoodMoralApplication::whereIn('application_status', [
        'Ready for Moderator Print',
        'Ready for Pickup'
      ])
      ->orderBy('updated_at', 'desc')
      ->get();
      
    // Get escalation notifications (students with 3+ minor violations)
    $escalationQuery = StudentViolation::select('student_id', 'first_name', 'last_name', 'department')
      ->selectRaw('COUNT(*) as violation_count')
      ->where('offense_type', 'minor')
      ->groupBy('student_id', 'first_name', 'last_name', 'department')
      ->havingRaw('COUNT(*) >= 3');
      
    $escalationNotifications = ViolationNotif::where('notif', 'LIKE', '%3 minor violations%')
      ->orWhere('notif', 'LIKE', '%escalated to major%')
      ->orderBy('created_at', 'desc')
      ->take(5)
      ->get();

    // Get trends analysis data for overall reports
    $trendsData = $this->getTrendsAnalysisData();
    $minorOffensesData = $this->getMinorOffensesTrendsData();

    return view('sec_osa.dashboard', compact(
      'applications', 'printApplications', 'site', 'sbahm', 'saste', 'snahs', 'som', 'gradsch',
      'minorPending', 'minorResolved', 'minorTotal', 'minorResolvedPercentage',
      'majorPending', 'majorResolved', 'majorTotal', 'majorResolvedPercentage',
      'majorViolationsByDept', 'minorViolationsByDept', 'escalationNotifications',
      'frequency', 'frequencyOptions', 'frequencyLabel', 'trendsData', 'minorOffensesData'
    ));
  }

  public function application()
  {
    // Get both SecOSAApplication (old system) and GoodMoralApplication (new system)
    $secOsaApplications = SecOSAApplication::with('receipt')->get();

    // Get ALL Good Moral Applications (not just ready for print) - moderator can view all statuses
    $goodMoralApplications = GoodMoralApplication::orderBy('updated_at', 'desc')->get();

    // Organize applications by status for better filtering
    $applicationsByStatus = [
      'pending' => $goodMoralApplications->whereNull('application_status'),
      'with_registrar' => $goodMoralApplications->where('application_status', 'With Registrar'),
      'approved_by_registrar' => $goodMoralApplications->where('application_status', 'Approved by Registrar'),
      'approved_by_dean' => $goodMoralApplications->where('application_status', 'Approved by Dean'),
      'approved_by_admin' => $goodMoralApplications->where('application_status', 'Approved by Administrator'),
      'ready_for_print' => $goodMoralApplications->where('application_status', 'Ready for Moderator Print'),
      'ready_for_pickup' => $goodMoralApplications->where('application_status', 'Ready for Pickup'),
      'rejected' => $goodMoralApplications->where('status', 'rejected'),
    ];

    // Combine and organize applications by type
    $applications = [
      'sec_osa' => $secOsaApplications,
      'good_moral' => $goodMoralApplications->where('certificate_type', 'good_moral'),
      'residency' => $goodMoralApplications->where('certificate_type', 'residency'),
      'all_good_moral' => $goodMoralApplications,
      'by_status' => $applicationsByStatus
    ];

    return view('sec_osa.Application', compact('applications'));
  }

  public function approve(Request $request, $id)
  {
    try {
      // 1. Find the application
      $application = SecOSAApplication::findOrFail($id);
      $studentDetails = StudentRegistration::where('student_id', $application->student_id)->first();
      $studentDetails1 = GoodMoralApplication::where('reference_number', $application->reference_number)->first();

      // 2. Update application status
      $application->status = 'approved';
      $application->save();

      // 3. Get current user
      $sec_osa = Auth::user();

      // 4. Prepare data for the PDF
      $data = [
        'title' => 'Application Approved',
        'application' => $application,
        'approved_by' => $sec_osa->fullname,
        'studentDetails' => $studentDetails,
        'studentDetails1' => $studentDetails1,
      ];

      // 5. Choose view based on account_type
      // Note: This appears to be legacy code for SecOSAApplication
      // For consistency with new system, keeping the same logic
      $view = ($studentDetails->account_type === 'student')
        ? 'pdf.student_certificate'
        : 'pdf.other_certificate';

      // 6. Generate PDF
      $pdf = Pdf::loadView($view, $data);
      Log::info('PDF generated successfully.');

      // 7. Ensure directory exists
      Storage::makeDirectory('public/pdfs');

      // 8. Save the file
      $filename = "application_{$id}.pdf";
      $relativePath = "public/pdfs/{$filename}";
      $saved = Storage::put($relativePath, $pdf->output());

      if ($saved) {
        $fullPath = Storage::path($relativePath);
        Log::info("PDF saved to: " . $fullPath);

        if (file_exists($fullPath)) {
          return response()->download($fullPath);
        } else {
          Log::error("File not found at path: $fullPath");
          return back()->withErrors("PDF saved but not found.");
        }
      } else {
        Log::error("PDF could not be saved.");
        return back()->withErrors("PDF could not be saved.");
      }
    } catch (\Exception $e) {
      Log::error("Approve Error: " . $e->getMessage());
      return back()->withErrors("An error occurred: " . $e->getMessage());
    }
  }



  public function reject($id)
  {
    $application = SecOSAApplication::findOrFail($id);
    $application->status = 'rejected';
    $application->save();

    return redirect()->route('sec_osa.dashboard')->with('status', 'Application rejected!');
  }

  public function major()
  {
    // Fetch all major violations with different statuses for comprehensive view
    // Include student relationship for year level information
    $students = StudentViolation::with('studentAccount')
      ->where('offense_type', 'major')
      ->orderBy('created_at', 'desc') // Most recent violations first
      ->paginate(10);

    // Count violations by status for dashboard stats
    $pendingCount = StudentViolation::where('offense_type', 'major')
      ->where('status', '0')
      ->count();

    $proceedingsUploadedCount = StudentViolation::where('offense_type', 'major')
      ->where('status', '1')
      ->count();

    $forwardedCount = StudentViolation::where('offense_type', 'major')
      ->where('status', '1.5')
      ->count();

    $closedCount = StudentViolation::where('offense_type', 'major')
      ->where('status', '2')
      ->count();

    return view('sec_osa.major', compact('students', 'pendingCount', 'proceedingsUploadedCount', 'forwardedCount', 'closedCount'));
  }

  /**
   * Show form to upload proceedings for a specific major violation
   */
  public function showUploadProceedings($id)
  {
    $violation = StudentViolation::findOrFail($id);

    // Ensure this is a major violation that needs proceedings
    if ($violation->offense_type !== 'major' || $violation->status !== '0') {
      return redirect()->route('sec_osa.major')
        ->with('error', 'This violation is not eligible for proceedings upload.');
    }

    return view('sec_osa.upload_proceedings', compact('violation'));
  }

  /**
   * Upload proceedings document for major violation
   */
  public function uploadProceedings(UploadProceedingsRequest $request, $id)
  {

    $violation = StudentViolation::findOrFail($id);

    // Store the uploaded file
    $file = $request->file('proceedings_document');
    $filename = 'proceedings_' . $violation->id . '_' . time() . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('violation_proceedings', $filename, 'public');

    // Generate case number if not exists
    if (!$violation->ref_num) {
      $date = date('Ymd');
      do {
        $unique = strtoupper(Str::random(6));
        $caseNumber = "CASE-{$date}-{$unique}";
        $exists = StudentViolation::where('ref_num', $caseNumber)->exists();
      } while ($exists);
      $violation->ref_num = $caseNumber;
    }

    // Update violation with proceedings info
    $violation->document_path = $path;
    $violation->status = '1'; // Proceedings uploaded, ready for admin forwarding
    $violation->meeting_date = $request->meeting_date;
    $violation->meeting_notes = $request->meeting_notes;
    $violation->proceedings_uploaded_by = Auth::user()->fullname;
    $violation->proceedings_uploaded_at = now();
    $violation->save();

    // Notify the student
    ViolationNotif::create([
      'ref_num' => $violation->ref_num,
      'student_id' => $violation->student_id,
      'status' => 0,
      'notif' => "Meeting proceedings for your major violation have been uploaded by the Moderator. Case Number: {$violation->ref_num}. The case is now under review.",
    ]);

    // Notify Program Coordinators of the student's department
    $progCoordinators = RoleAccount::where('account_type', 'prog_coor')
      ->where('department', $violation->department)
      ->get();

    foreach ($progCoordinators as $coordinator) {
      // Only create notification if coordinator has a student_id (some staff accounts might not have one)
      if ($coordinator->student_id) {
        ViolationNotif::create([
          'ref_num' => $violation->ref_num,
          'student_id' => $coordinator->student_id,
          'status' => 0,
          'notif' => "Major violation proceedings uploaded for student {$violation->first_name} {$violation->last_name} ({$violation->student_id}) from your department. Case: {$violation->ref_num}.",
        ]);
      }
    }

    return redirect()->route('sec_osa.major')
      ->with('success', "Proceedings uploaded successfully! Case Number: {$violation->ref_num}");
  }

  /**
   * Download proceedings document uploaded by moderator
   */
  public function downloadProceedings($id)
  {
    $violation = StudentViolation::findOrFail($id);

    // Check if proceedings document exists
    if (!$violation->document_path || !Storage::disk('public')->exists($violation->document_path)) {
      return redirect()->back()->with('error', 'Proceedings document not found.');
    }

    // Ensure this is a proceedings file (should be in violation_proceedings directory)
    if (!str_contains($violation->document_path, 'violation_proceedings/')) {
      return redirect()->back()->with('error', 'Invalid proceedings document.');
    }

    return Storage::disk('public')->download($violation->document_path);
  }

  /**
   * Forward major violation to admin for case closure
   */
  public function forwardToAdmin($id)
  {
    $violation = StudentViolation::findOrFail($id);

    // Ensure this is a major violation with proceedings
    if ($violation->offense_type !== 'major' || $violation->status !== '1' || !$violation->document_path) {
      return redirect()->route('sec_osa.major')
        ->with('error', 'This violation is not eligible for forwarding to admin.');
    }

    // Update status to indicate it's been forwarded to admin
    $violation->status = '1.5'; // Intermediate status: forwarded to admin
    $violation->forwarded_to_admin_at = now();
    $violation->forwarded_by = Auth::user()->fullname;
    $violation->save();

    // Notify admins
    $admins = RoleAccount::where('account_type', 'admin')->get();
    foreach ($admins as $admin) {
      // Only create notification if admin has a student_id (some staff accounts might not have one)
      if ($admin->student_id) {
        ViolationNotif::create([
          'ref_num' => $violation->ref_num,
          'student_id' => $admin->student_id,
          'status' => 0,
          'notif' => "Major violation case forwarded by Moderator for closure. Case: {$violation->ref_num}. Student: {$violation->first_name} {$violation->last_name} ({$violation->student_id}). Please review proceedings and close the case.",
        ]);
      }
    }

    // Notify the student
    ViolationNotif::create([
      'ref_num' => $violation->ref_num,
      'student_id' => $violation->student_id,
      'status' => 0,
      'notif' => "Your major violation case has been forwarded to the Administrator for final review and closure. Case Number: {$violation->ref_num}.",
    ]);

    return redirect()->route('sec_osa.major')
      ->with('success', "Case {$violation->ref_num} has been forwarded to Admin for closure.");
  }

  public function minor()
  {
    // Fetch all minor violations with different statuses for comprehensive view
    // Include student relationship for year level information
    $students = StudentViolation::with('studentAccount')
      ->where('offense_type', 'minor')
      ->orderBy('created_at', 'desc') // Most recent violations first
      ->paginate(10);

    // Count violations by status for dashboard stats
    $pendingCount = StudentViolation::where('offense_type', 'minor')
      ->where('status', '0')
      ->count();

    $approvedCount = StudentViolation::where('offense_type', 'minor')
      ->where('status', '1')
      ->count();

    $closedCount = StudentViolation::where('offense_type', 'minor')
      ->where('status', '2')
      ->count();

    return view('sec_osa.minor', compact('students', 'pendingCount', 'approvedCount', 'closedCount'));
  }
  public function uploadDocument(UploadViolationDocumentRequest $request, $id)
  {

    $violation = StudentViolation::findOrFail($id);

    $path = $request->file('document')->store('violations_documents', 'public');

    $date = date('Ymd');

    // Generate unique case number with a retry loop
    do {
      $unique = strtoupper(Str::random(6));  // 6 random uppercase letters/numbers
      $caseNumber = "CASE-{$date}-{$unique}";
      $exists = StudentViolation::where('ref_num', $caseNumber)->exists();
    } while ($exists);

    $violation->document_path = $path;
    $violation->ref_num = $caseNumber;
    $violation->status = "1";

    $violation->save();

    ViolationNotif::create([
      'ref_num' => $caseNumber,
      'student_id' => $violation->student_id,
      'status' => 0,  // initial status
      'notif' => "Uploaded the proceedings with case number: $caseNumber",
    ]);

    return back()->with('success', "Document uploaded successfully! Case No: {$caseNumber}");
  }

  public function searchMinor(Request $request)
  {
    $query = StudentViolation::where('offense_type', 'minor');

    // Basic search fields
    if ($request->filled('ref_num')) {
      $query->where('ref_num', 'like', '%' . $request->ref_num . '%');
    }

    if ($request->filled('student_id')) {
      $query->where('student_id', 'like', '%' . $request->student_id . '%');
    }

    if ($request->filled('last_name')) {
      $query->where('last_name', 'like', '%' . $request->last_name . '%');
    }

    if ($request->filled('first_name')) {
      $query->where('first_name', 'like', '%' . $request->first_name . '%');
    }

    // Department filter
    if ($request->filled('department') && $request->department !== '') {
      $query->where('department', $request->department);
    }

    // Status filter
    if ($request->filled('status') && $request->status !== '') {
      $query->where('status', $request->status);
    }

    // Added by filter
    if ($request->filled('added_by')) {
      $query->where('added_by', 'like', '%' . $request->added_by . '%');
    }

    // Violation count filter (count violations per student)
    if ($request->filled('violation_count') && $request->violation_count !== '') {
      $countFilter = $request->violation_count;
      
      if ($countFilter === '1') {
        // Students with exactly 1 violation
        $query->whereIn('student_id', function($subquery) {
          $subquery->select('student_id')
                   ->from('student_violations')
                   ->where('offense_type', 'minor')
                   ->groupBy('student_id')
                   ->havingRaw('COUNT(*) = 1');
        });
      } elseif ($countFilter === '2-3') {
        // Students with 2-3 violations
        $query->whereIn('student_id', function($subquery) {
          $subquery->select('student_id')
                   ->from('student_violations')
                   ->where('offense_type', 'minor')
                   ->groupBy('student_id')
                   ->havingRaw('COUNT(*) BETWEEN 2 AND 3');
        });
      } elseif ($countFilter === '4+') {
        // Students with 4 or more violations
        $query->whereIn('student_id', function($subquery) {
          $subquery->select('student_id')
                   ->from('student_violations')
                   ->where('offense_type', 'minor')
                   ->groupBy('student_id')
                   ->havingRaw('COUNT(*) >= 4');
        });
      }
    }

    // Date range filter
    if ($request->filled('date_from')) {
      $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
      $query->whereDate('created_at', '<=', $request->date_to);
    }

    $students = $query->orderBy('created_at', 'desc')->paginate(10); // Most recent violations first

    // Count violations by status for dashboard stats
    $pendingCount = StudentViolation::where('offense_type', 'minor')->where('status', '0')->count();
    $approvedCount = StudentViolation::where('offense_type', 'minor')->where('status', '1')->count();
    $closedCount = StudentViolation::where('offense_type', 'minor')->where('status', '2')->count();

    return view('sec_osa.minor', compact('students', 'pendingCount', 'approvedCount', 'closedCount'));
  }

  public function searchMajor(Request $request)
  {
    $query = StudentViolation::where('offense_type', 'major');

    // Basic student info search
    if ($request->filled('first_name')) {
      $query->where('first_name', 'like', '%' . $request->first_name . '%');
    }

    if ($request->filled('last_name')) {
      $query->where('last_name', 'like', '%' . $request->last_name . '%');
    }

    if ($request->filled('student_id')) {
      $query->where('student_id', 'like', '%' . $request->student_id . '%');
    }

    // Department filter
    if ($request->filled('department') && $request->department !== '') {
      $query->where('department', $request->department);
    }

    // Status filter
    if ($request->filled('status') && $request->status !== '') {
      $query->where('status', $request->status);
    }

    // Proceedings filter
    if ($request->filled('has_proceedings') && $request->has_proceedings !== '') {
      if ($request->has_proceedings === 'yes') {
        $query->whereNotNull('document_path');
      } else {
        $query->whereNull('document_path');
      }
    }

    // Date range filter
    if ($request->filled('date_from')) {
      $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
      $query->whereDate('created_at', '<=', $request->date_to);
    }

    $students = $query->orderBy('created_at', 'desc')->paginate(10); // Most recent violations first

    // Count violations by status for dashboard stats
    $pendingCount = StudentViolation::where('offense_type', 'major')->where('status', '0')->count();
    $proceedingsUploadedCount = StudentViolation::where('offense_type', 'major')->where('status', '1')->count();
    $forwardedCount = StudentViolation::where('offense_type', 'major')->where('status', '1.5')->count();
    $closedCount = StudentViolation::where('offense_type', 'major')->where('status', '2')->count();

    return view('sec_osa.major', compact('students', 'pendingCount', 'proceedingsUploadedCount', 'forwardedCount', 'closedCount'));
  }

  public function printCertificate($id)
  {
    return $this->certificateService->generateCertificate(
      $id,
      'download',
      ['Ready for Moderator Print', 'Ready for Pickup'],
      'sec_osa.dashboard',
      false
    );
  }

  /**
   * Download certificate for already printed applications (allows multiple downloads)
   */
  public function downloadCertificate($id)
  {
    return $this->certificateService->generateCertificate(
      $id,
      'download',
      ['Ready for Pickup', 'Ready for Moderator Print'],
      'sec_osa.dashboard',
      false
    );
  }
  
  /**
   * Show escalation notifications for students with 3 minor violations
   */
  public function escalationNotifications()
  {
    $escalationNotifications = $this->violationService->getEscalationNotificationsList();

    return view('sec_osa.escalationNotifications', compact('escalationNotifications'));
  }
  
  /**
   * Get notification counts for the moderator navbar
   */
  public function getNotificationCounts()
  {
    // Count pending minor violations
    $pendingMinorViolations = StudentViolation::where('offense_type', 'minor')
      ->where('status', 0) // Pending status
      ->count();
      
    // Count pending major violations
    $pendingMajorViolations = StudentViolation::where('offense_type', 'major')
      ->where('status', 0) // Pending status
      ->count();
      
    // Count students with 3+ minor violations (escalation notifications)
    $escalationNotifications = StudentViolation::select('student_id')
      ->where('offense_type', 'minor')
      ->groupBy('student_id')
      ->havingRaw('COUNT(*) >= 3')
      ->count();
      
    // Count applications ready for printing
    $printReadyApplications = GoodMoralApplication::where('application_status', 'Ready for Moderator Print')
      ->count();

    return response()->json([
      'pendingMinorViolations' => $pendingMinorViolations,
      'pendingMajorViolations' => $pendingMajorViolations,
      'escalationNotifications' => $escalationNotifications,
      'printReadyApplications' => $printReadyApplications,
    ]);
  }

  /**
   * Show violations for a specific department
   */
  public function viewDepartmentViolations($department)
  {
    // Include student relationship for year level information
    $violations = StudentViolation::with('studentAccount')
      ->where('department', $department)
      ->orderBy('created_at', 'desc')
      ->paginate(15);

    return view('sec_osa.departmentViolations', compact('violations', 'department'));
  }

  /**
   * Show all violations view
   */
  public function violation()
  {
    // Include student relationship for year level information
    $violations = StudentViolation::with('studentAccount')
      ->orderBy('created_at', 'desc')
      ->paginate(15);
    
    return view('sec_osa.violations', compact('violations'));
  }

  /**
   * Show moderator profile - Access permitted for all authenticated users
   */
  public function profile()
  {
    // Skip the automatic role check - all authenticated users can view this page
    $user = Auth::user();
    
    // Log detailed information for troubleshooting
    Log::info('Profile accessed', [
      'user_id' => $user->id,
      'account_type' => $user->account_type,
      'path' => request()->path(),
      'user_name' => $user->name,
      'user_email' => $user->email
    ]);
    
    // This view doesn't contain sensitive information so it's safe to bypass the role check
    return view('sec_osa.profile', ['user' => $user]);
  }

  /**
   * Update moderator profile information
   */
  public function updateProfile(UpdateStaffProfileRequest $request)
  {
    $user = Auth::user();

    // Find the user in roleaccounts table and update
    $userRecord = RoleAccount::find($user->id);
    $userRecord->fullname = $request->fullname;
    $userRecord->department = $request->department;
    $userRecord->gender = $request->gender;
    $userRecord->save();

    return redirect()->route('sec_osa.profile')->with('status', 'profile-updated');
  }

  /**
   * Update moderator email address
   */
  public function updateEmail(UpdateModeratorEmailRequest $request)
  {
    $user = Auth::user();

    // Verify current password manually
    if (!Hash::check($request->current_password, $user->password)) {
      return back()->withErrors(['current_password' => 'The current password is incorrect.']);
    }

    // Find the user in roleaccounts table and update
    $userRecord = RoleAccount::find($user->id);
    $userRecord->email = $request->email;
    $userRecord->save();

    return redirect()->route('sec_osa.profile')->with('status', 'email-updated');
  }

  /**
   * Update moderator password
   */
  public function updatePassword(UpdateModeratorPasswordRequest $request)
  {
    $user = Auth::user();

    // Verify current password manually
    if (!Hash::check($request->current_password, $user->password)) {
      return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
    }

    // Find the user in roleaccounts table and update
    $userRecord = RoleAccount::find($user->id);
    $userRecord->password = Hash::make($request->password);
    $userRecord->save();

    return redirect()->route('sec_osa.profile')->with('status', 'password-updated');
  }

  /**
   * Get trends analysis data for major violations
   */
  private function getTrendsAnalysisData()
  {
    // Get current academic year and previous academic year data
    $currentYear = date('Y');
    $previousYear = $currentYear - 1;
    
    // Define academic year periods
    $previousAcademicYearStart = "{$previousYear}-08-01"; // Aug 2023
    $previousAcademicYearEnd = "{$currentYear}-07-31";   // Jul 2024
    $currentAcademicYearStart = "{$currentYear}-08-01";  // Aug 2024
    $currentDate = date('Y-m-d');
    
    // Get all departments
    $departments = ['SITE', 'SBAHM', 'SNAHS', 'SASTE'];
    
    // Define population totals as per requirements - ALWAYS use these values
    $departmentPopulation = [
      'SITE' => 640,
      'SBAHM' => 727, 
      'SNAHS' => 2831,
      'SASTE' => 409
    ];
    
    // Define Previous AY (AY 2023-2024) Major Offenses data - Official numbers
    $previousYearViolations = [
      'SITE' => 9,
      'SBAHM' => 15,
      'SNAHS' => 79,
      'SASTE' => 4
    ];
    
    // Get violation data for current academic year (major offenses only) - Real-time from database
    $currentYearViolations = \App\Models\StudentViolation::where('offense_type', 'major')
      ->where('created_at', '>=', $currentAcademicYearStart)
      ->where('created_at', '<=', $currentDate)
      ->selectRaw('department, count(distinct student_id) as violator_count')
      ->groupBy('department')
      ->pluck('violator_count', 'department')
      ->toArray();

    $trendsData = [];
    foreach ($departments as $dept) {
      $totalPopulation = $departmentPopulation[$dept] ?? 0;
      $previousViolators = $previousYearViolations[$dept] ?? 0;
      $currentViolators = $currentYearViolations[$dept] ?? 0;
      
      // Calculate variance using the correct formula: ((Previous AY - Current AY) / Previous AY) × 100
      $rawDifference = $currentViolators - $previousViolators;
      $variancePercentage = $previousViolators > 0
        ? round((($previousViolators - $currentViolators) / $previousViolators) * 100, 2)
        : ($currentViolators > 0 ? -100.00 : 0.00);

      $trendsData[$dept] = [
        'department' => $dept,
        'total_population' => $totalPopulation,
        'violators_2023_2024' => $previousViolators,
        'violators_june_2025' => $currentViolators,
        'current_violators' => $currentViolators, // AY 2025-2026 (same as current for now)
        'variance_june' => $rawDifference,
        'variance_percentage_june' => $variancePercentage,
        'trend_june' => $rawDifference > 0 ? 'increase' : ($rawDifference < 0 ? 'decrease' : 'stable')
      ];
    }

    // Create chart data for visualization
    $chartLabels = ["A.Y. {$previousYear}-{$currentYear}", "As of " . date('F Y')];
    $chartDatasets = [];

    foreach ($departments as $dept) {
      $chartDatasets[$dept] = [
        $previousYearViolations[$dept] ?? 0,
        $currentYearViolations[$dept] ?? 0
      ];
    }

    return [
      'departments_data' => $trendsData,
      'chart_labels' => $chartLabels,
      'chart_datasets' => $chartDatasets,
      'total_summary' => [
        'total_population' => array_sum(array_column($trendsData, 'total_population')),
        'total_violators_2023_2024' => array_sum(array_column($trendsData, 'violators_2023_2024')),
        'total_violators_june_2025' => array_sum(array_column($trendsData, 'violators_june_2025')),
        'total_current_violators' => array_sum(array_column($trendsData, 'current_violators')),
        'total_variance_june' => array_sum(array_column($trendsData, 'variance_june'))
      ]
    ];
  }

  /**
   * Get minor offenses trends analysis data
   */
  private function getMinorOffensesTrendsData()
  {
    // Get current academic year and previous academic year data
    $currentYear = date('Y');
    $previousYear = $currentYear - 1;
    
    // Define academic year periods
    $previousAcademicYearStart = "{$previousYear}-08-01"; // Aug 2023
    $previousAcademicYearEnd = "{$currentYear}-07-31";   // Jul 2024
    $currentAcademicYearStart = "{$currentYear}-08-01";  // Aug 2024
    $currentDate = date('Y-m-d');
    
    // Get all departments
    $departments = ['SITE', 'SBAHM', 'SNAHS', 'SASTE'];
    
    // Define population totals as per requirements - ALWAYS use these values
    $departmentPopulation = [
      'SITE' => 640,
      'SBAHM' => 727, 
      'SNAHS' => 2831,
      'SASTE' => 409
    ];
    
    // Define Previous AY (AY 2023-2024) Minor Offenses data - Official numbers
    $previousYearViolations = [
      'SITE' => 118,
      'SBAHM' => 88,
      'SNAHS' => 524,
      'SASTE' => 97
    ];
    
    // Get violation data for current academic year (minor offenses only) - Real-time from database
    $currentYearViolations = \App\Models\StudentViolation::where('offense_type', 'minor')
      ->where('created_at', '>=', $currentAcademicYearStart)
      ->where('created_at', '<=', $currentDate)
      ->selectRaw('department, count(distinct student_id) as violator_count')
      ->groupBy('department')
      ->pluck('violator_count', 'department')
      ->toArray();

    $minorOffensesData = [];
    foreach ($departments as $dept) {
      $totalPopulation = $departmentPopulation[$dept] ?? 0;
      $previousViolators = $previousYearViolations[$dept] ?? 0;
      $currentViolators = $currentYearViolations[$dept] ?? 0;
      
      // Calculate variance using the correct formula: ((Previous AY - Current AY) / Previous AY) × 100
      $rawDifference = $currentViolators - $previousViolators;
      $variancePercentage = $previousViolators > 0
        ? round((($previousViolators - $currentViolators) / $previousViolators) * 100, 2)
        : ($currentViolators > 0 ? -100.00 : 0.00);

      // Calculate percentage of population affected
      $currentPopulationPercentage = $totalPopulation > 0
        ? round(($currentViolators / $totalPopulation) * 100, 2)
        : 0;
      $previousPopulationPercentage = $totalPopulation > 0
        ? round(($previousViolators / $totalPopulation) * 100, 2)
        : 0;

      $minorOffensesData[$dept] = [
        'department' => $dept,
        'total_population' => $totalPopulation,
        'violators_2023_2024' => $previousViolators,
        'violators_june_2025' => $currentViolators,
        'current_violators' => $currentViolators, // AY 2025-2026 (same as current for now)
        'variance' => $rawDifference,
        'variance_percentage' => $variancePercentage,
        'trend' => $rawDifference > 0 ? 'increase' : ($rawDifference < 0 ? 'decrease' : 'stable'),
        'current_population_percentage' => $currentPopulationPercentage,
        'previous_population_percentage' => $previousPopulationPercentage
      ];
    }

    return [
      'departments_data' => $minorOffensesData,
      'total_summary' => [
        'total_population' => array_sum(array_column($minorOffensesData, 'total_population')),
        'total_violators_2023_2024' => array_sum(array_column($minorOffensesData, 'violators_2023_2024')),
        'total_violators_june_2025' => array_sum(array_column($minorOffensesData, 'violators_june_2025')),
        'total_current_violators' => array_sum(array_column($minorOffensesData, 'current_violators')),
        'total_variance' => array_sum(array_column($minorOffensesData, 'variance'))
      ]
    ];
  }
}


