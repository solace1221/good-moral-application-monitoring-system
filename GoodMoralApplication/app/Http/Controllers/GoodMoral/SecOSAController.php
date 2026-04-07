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

class SecOSAController extends Controller
{

  use RoleCheck;

  public function __construct()
  {
    // Apply middleware to all methods except profile
    $action = request()->route() ? request()->route()->getActionMethod() : null;
    
    // Skip role check if accessing profile method
    if ($action !== 'profile') {
      // Enable role check to ensure proper authorization for other methods
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
    
    //Applicants per department (using GoodMoralApplication for new system)
    $site = GoodMoralApplication::where('department', 'SITE')->count();
    $saste = GoodMoralApplication::where('department', 'SASTE')->count();
    $sbahm = GoodMoralApplication::where('department', 'SBAHM')->count();
    $snahs = GoodMoralApplication::where('department', 'SNAHS')->count();
    $som = GoodMoralApplication::where('department', 'SOM')->count();
    $gradsch = GoodMoralApplication::where('department', 'GRADSCH')->count();

    // Minor violations statistics
    $minorPending = StudentViolation::where('status', '!=', 2)->where('offense_type', 'minor')->count();
    $minorResolved = StudentViolation::where('status', '=', 2)->where('offense_type', 'minor')->count();
    $minorTotal = $minorPending + $minorResolved;
    $minorResolvedPercentage = $minorTotal > 0 ? round(($minorResolved / $minorTotal) * 100, 1) : 0;

    // Major violations statistics
    $majorPending = StudentViolation::where('status', '!=', 2)->where('offense_type', 'major')->count();
    $majorResolved = StudentViolation::where('status', '=', 2)->where('offense_type', 'major')->count();
    $majorTotal = $majorPending + $majorResolved;
    $majorResolvedPercentage = $majorTotal > 0 ? round(($majorResolved / $majorTotal) * 100, 1) : 0;

    // Violations by department (using department field directly from student_violations table)
    $majorViolationsByDept = [
      'SITE' => StudentViolation::where('offense_type', 'major')->where('department', 'SITE')->count(),
      'SASTE' => StudentViolation::where('offense_type', 'major')->where('department', 'SASTE')->count(),
      'SBAHM' => StudentViolation::where('offense_type', 'major')->where('department', 'SBAHM')->count(),
      'SNAHS' => StudentViolation::where('offense_type', 'major')->where('department', 'SNAHS')->count(),
      'SOM' => StudentViolation::where('offense_type', 'major')->where('department', 'SOM')->count(),
      'GRADSCH' => StudentViolation::where('offense_type', 'major')->where('department', 'GRADSCH')->count(),
    ];

    $minorViolationsByDept = [
      'SITE' => StudentViolation::where('offense_type', 'minor')->where('department', 'SITE')->count(),
      'SASTE' => StudentViolation::where('offense_type', 'minor')->where('department', 'SASTE')->count(),
      'SBAHM' => StudentViolation::where('offense_type', 'minor')->where('department', 'SBAHM')->count(),
      'SNAHS' => StudentViolation::where('offense_type', 'minor')->where('department', 'SNAHS')->count(),
      'SOM' => StudentViolation::where('offense_type', 'minor')->where('department', 'SOM')->count(),
      'GRADSCH' => StudentViolation::where('offense_type', 'minor')->where('department', 'GRADSCH')->count(),
    ];

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
  public function uploadProceedings(Request $request, $id)
  {
    $request->validate([
      'proceedings_document' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
      'meeting_date' => 'required|date',
      'meeting_notes' => 'nullable|string|max:1000',
    ]);

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
  public function uploadDocument(Request $request, $id)
  {
    $request->validate([
      'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

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
    try {
      Log::info("Print Certificate Started for ID: {$id}");

      // Find the GoodMoralApplication
      $application = GoodMoralApplication::findOrFail($id);
      Log::info("Application found", ['application_id' => $application->id, 'status' => $application->application_status]);

      // Check if the application is ready for moderator print or already printed (allow reprint)
      if (!in_array($application->application_status, ['Ready for Moderator Print', 'Ready for Pickup'])) {
        Log::warning("Application not ready for print", ['status' => $application->application_status]);
        return redirect()->route('sec_osa.dashboard')->with('error', 'Application is not ready for printing!');
      }

      // Check if receipt exists
      $receipt = \App\Models\Receipt::where('reference_num', $application->reference_number)->first();
      if (!$receipt || !$receipt->document_path) {
        Log::error("Receipt not found", ['reference_number' => $application->reference_number]);
        return redirect()->route('sec_osa.dashboard')->with('error', 'Payment receipt not found!');
      }
      Log::info("Receipt found", ['receipt_id' => $receipt->id]);

      // Get student details
      $studentDetails = \App\Models\RoleAccount::where('student_id', $application->student_id)->first();
      if (!$studentDetails) {
        Log::error("Student details not found", ['student_id' => $application->student_id]);
        return redirect()->route('sec_osa.dashboard')->with('error', 'Student details not found!');
      }
      Log::info("Student details found", ['student_id' => $studentDetails->student_id, 'account_type' => $studentDetails->account_type]);

      // Get current moderator
      $moderator = Auth::user();
      Log::info("Moderator info", ['moderator_id' => $moderator->id, 'moderator_name' => $moderator->fullname]);

      // Prepare data for the PDF
      $data = [
        'title' => $application->certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency',
        'application' => $application,
        'receipt' => $receipt,
        'printed_by' => $moderator->fullname,
        'studentDetails' => $studentDetails,
        'studentDetails1' => $application, // The template expects this for semester info
        'print_date' => now()->format('F j, Y'),
        'reasons_array' => $application->reasons_array, // Pass individual reasons
        'number_of_copies' => (int)$application->number_of_copies,
      ];
      Log::info("PDF data prepared", ['title' => $data['title']]);

      // Check if student/alumni has unresolved violations
      $hasUnresolvedViolations = \App\Models\StudentViolation::where('student_id', $application->student_id)
        ->where('status', '!=', 2) // status 2 = fully resolved
        ->exists();
      Log::info("Violation check", ['student_id' => $application->student_id, 'has_violations' => $hasUnresolvedViolations]);

      // Choose view based on certificate type, account type, and violation status
      if ($application->certificate_type === 'good_moral') {
        // Good Moral Certificate (only for those without violations)
        $view = 'pdf.student_certificate';
      } elseif ($application->certificate_type === 'residency') {
        // Certificate of Residency - different templates for students vs alumni
        if ($studentDetails->account_type === 'student') {
          // Students with violations get simple residency certificate
          $view = 'pdf.student_residency_certificate';
        } else {
          // Alumni with violations get the existing residency certificate
          $view = 'pdf.other_certificate';
        }
      } else {
        // Fallback logic for legacy applications
        if ($hasUnresolvedViolations) {
          $view = $studentDetails->account_type === 'student' ? 'pdf.student_residency_certificate' : 'pdf.other_certificate';
        } else {
          $view = 'pdf.student_certificate';
        }
      }
      Log::info("Template selected", [
        'view' => $view,
        'certificate_type' => $application->certificate_type,
        'account_type' => $studentDetails->account_type,
        'has_violations' => $hasUnresolvedViolations
      ]);

      // Check if view exists
      if (!view()->exists($view)) {
        Log::error("View does not exist", ['view' => $view]);
        return redirect()->route('sec_osa.dashboard')->with('error', "PDF template '{$view}' not found!");
      }

      // Generate PDF
      Log::info("Starting PDF generation");
      $pdf = Pdf::loadView($view, $data);
      $pdf->setPaper('letter', 'portrait');
      Log::info("PDF generated successfully");

      // Update application status to ready for pickup (only on first print)
      if ($application->application_status === 'Ready for Moderator Print') {
        $application->application_status = 'Ready for Pickup';
        $application->save();
        Log::info("Application status updated to Ready for Pickup");

        // Create notification for student - certificate printed and ready for pickup
        NotifArchive::create([
          'number_of_copies' => $application->number_of_copies,
          'reference_number' => $application->reference_number,
          'fullname' => $application->fullname,
          'gender' => $application->gender, // Add gender field
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
          'status' => '5', // Status 5 = Certificate printed and ready for pickup
        ]);
        Log::info("Notification created for student");
      } else {
        Log::info("Reprint - status and notification unchanged");
      }

      // Generate filename
      $certificateType = $application->certificate_type === 'good_moral' ? 'GoodMoral' : 'Residency';
      $filename = "{$certificateType}_Certificate_{$application->student_id}_{$application->reference_number}.pdf";
      Log::info("Filename generated", ['filename' => $filename]);

      // Try to download the PDF
      try {
        Log::info("Attempting PDF download");
        $response = $pdf->download($filename);
        Log::info("PDF download successful");
        return $response;
      } catch (\Exception $downloadError) {
        Log::error("PDF download failed", ['error' => $downloadError->getMessage()]);

        // Try alternative: stream the PDF
        try {
          Log::info("Attempting PDF stream as fallback");
          return $pdf->stream($filename);
        } catch (\Exception $streamError) {
          Log::error("PDF stream also failed", ['error' => $streamError->getMessage()]);
          throw new \Exception("Both download and stream failed: " . $downloadError->getMessage() . " | " . $streamError->getMessage());
        }
      }

    } catch (\Exception $e) {
      Log::error("Print Certificate Error", [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
      ]);
      return redirect()->route('sec_osa.dashboard')->with('error', 'An error occurred while printing the certificate: ' . $e->getMessage());
    }
  }

  /**
   * Download certificate for already printed applications (allows multiple downloads)
   */
  public function downloadCertificate($id)
  {
    try {
      Log::info("Download Certificate Started for ID: {$id}");

      // Find the GoodMoralApplication
      $application = GoodMoralApplication::findOrFail($id);
      Log::info("Application found", ['application_id' => $application->id, 'status' => $application->application_status]);

      // Check if the application is ready for pickup (already printed) or ready for print
      if (!in_array($application->application_status, ['Ready for Pickup', 'Ready for Moderator Print'])) {
        Log::warning("Application not available for download", ['status' => $application->application_status]);
        return redirect()->route('sec_osa.dashboard')->with('error', 'Certificate is not available for download!');
      }

      // Check if receipt exists
      $receipt = \App\Models\Receipt::where('reference_num', $application->reference_number)->first();
      if (!$receipt || !$receipt->document_path) {
        Log::error("Receipt not found", ['reference_number' => $application->reference_number]);
        return redirect()->route('sec_osa.dashboard')->with('error', 'Payment receipt not found!');
      }
      Log::info("Receipt found", ['receipt_id' => $receipt->id]);

      // Get student details
      $studentDetails = \App\Models\RoleAccount::where('student_id', $application->student_id)->first();
      if (!$studentDetails) {
        Log::error("Student details not found", ['student_id' => $application->student_id]);
        return redirect()->route('sec_osa.dashboard')->with('error', 'Student details not found!');
      }
      Log::info("Student details found", ['student_id' => $studentDetails->student_id, 'account_type' => $studentDetails->account_type]);

      // Get current moderator
      $moderator = Auth::user();
      Log::info("Moderator info", ['moderator_id' => $moderator->id, 'moderator_name' => $moderator->fullname]);

      // Prepare data for the PDF
      $data = [
        'title' => $application->certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency',
        'application' => $application,
        'receipt' => $receipt,
        'printed_by' => $moderator->fullname,
        'studentDetails' => $studentDetails,
        'studentDetails1' => $application, // The template expects this for semester info
        'print_date' => now()->format('F j, Y'),
        'reasons_array' => $application->reasons_array, // Pass individual reasons
        'number_of_copies' => (int)$application->number_of_copies,
      ];
      Log::info("PDF data prepared", ['title' => $data['title']]);

      // Check if student/alumni has unresolved violations
      $hasUnresolvedViolations = \App\Models\StudentViolation::where('student_id', $application->student_id)
        ->where('status', '!=', 2) // status 2 = fully resolved
        ->exists();
      Log::info("Violation check", ['student_id' => $application->student_id, 'has_violations' => $hasUnresolvedViolations]);

      // Choose view based on certificate type, account type, and violation status
      if ($application->certificate_type === 'good_moral') {
        // Good Moral Certificate (only for those without violations)
        $view = 'pdf.student_certificate';
      } elseif ($application->certificate_type === 'residency') {
        // Certificate of Residency - different templates for students vs alumni
        if ($studentDetails->account_type === 'student') {
          // Students with violations get simple residency certificate
          $view = 'pdf.student_residency_certificate';
        } else {
          // Alumni with violations get the existing residency certificate
          $view = 'pdf.other_certificate';
        }
      } else {
        // Fallback logic for legacy applications
        if ($hasUnresolvedViolations) {
          $view = $studentDetails->account_type === 'student' ? 'pdf.student_residency_certificate' : 'pdf.other_certificate';
        } else {
          $view = 'pdf.student_certificate';
        }
      }
      Log::info("Template selected", [
        'view' => $view,
        'certificate_type' => $application->certificate_type,
        'account_type' => $studentDetails->account_type,
        'has_violations' => $hasUnresolvedViolations
      ]);

      // Check if view exists
      if (!view()->exists($view)) {
        Log::error("View does not exist", ['view' => $view]);
        return redirect()->route('sec_osa.dashboard')->with('error', "PDF template '{$view}' not found!");
      }

      // Generate PDF
      Log::info("Starting PDF generation");
      $pdf = Pdf::loadView($view, $data);
      $pdf->setPaper('letter', 'portrait');
      Log::info("PDF generated successfully");

      // Generate filename
      $certificateType = $application->certificate_type === 'good_moral' ? 'GoodMoral' : 'Residency';
      $filename = "{$certificateType}_Certificate_{$application->student_id}_{$application->reference_number}.pdf";
      Log::info("Filename generated", ['filename' => $filename]);

      // Try to download the PDF
      try {
        Log::info("Attempting PDF download");
        $response = $pdf->download($filename);
        Log::info("PDF download successful");
        return $response;
      } catch (\Exception $downloadError) {
        Log::error("PDF download failed", ['error' => $downloadError->getMessage()]);

        // Try alternative: stream the PDF
        try {
          Log::info("Attempting PDF stream as fallback");
          return $pdf->stream($filename);
        } catch (\Exception $streamError) {
          Log::error("PDF stream also failed", ['error' => $streamError->getMessage()]);
          throw new \Exception("Both download and stream failed: " . $downloadError->getMessage() . " | " . $streamError->getMessage());
        }
      }

    } catch (\Exception $e) {
      Log::error("Download Certificate Error", [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
      ]);
      return redirect()->route('sec_osa.dashboard')->with('error', 'An error occurred while downloading the certificate: ' . $e->getMessage());
    }
  }
  
  /**
   * Show escalation notifications for students with 3 minor violations
   */
  public function escalationNotifications()
  {
    // Get all students who have 3 or more minor violations
    $escalatedStudents = StudentViolation::select('student_id', 'first_name', 'last_name', 'department', 'course')
      ->selectRaw('COUNT(*) as minor_violation_count')
      ->where('offense_type', 'minor')
      ->groupBy('student_id', 'first_name', 'last_name', 'department', 'course')
      ->havingRaw('COUNT(*) >= 3')
      ->orderBy('minor_violation_count', 'desc')
      ->get();

    // For each student, get their violation details and check if major violation was created
    $escalationNotifications = [];
    foreach ($escalatedStudents as $student) {
      // Get all minor violations for this student
      $minorViolations = StudentViolation::where('student_id', $student->student_id)
        ->where('offense_type', 'minor')
        ->orderBy('created_at', 'desc')
        ->get();

      // Check if a major violation was automatically created for this student
      $autoMajorViolation = StudentViolation::where('student_id', $student->student_id)
        ->where('offense_type', 'major')
        ->where('violation', 'LIKE', '%Escalated from 3 minor violations%')
        ->first();

      $escalationNotifications[] = [
        'student_id' => $student->student_id,
        'fullname' => $student->first_name . ' ' . $student->last_name,
        'department' => $student->department,
        'course' => $student->course,
        'minor_violation_count' => $student->minor_violation_count,
        'minor_violations' => $minorViolations,
        'auto_major_violation' => $autoMajorViolation,
        'escalation_status' => $autoMajorViolation ? 'escalated' : 'pending_escalation',
        'latest_violation_date' => $minorViolations->first()->created_at ?? null,
      ];
    }

    // Sort by latest violation date (most recent first)
    usort($escalationNotifications, function($a, $b) {
      if (!$a['latest_violation_date'] || !$b['latest_violation_date']) {
        return 0;
      }
      return $b['latest_violation_date']->timestamp - $a['latest_violation_date']->timestamp;
    });

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
  public function updateProfile(Request $request)
  {
    $user = Auth::user();
    
    $request->validate([
      'fullname' => 'required|string|max:255',
      'department' => 'required|string|max:255',
      'gender' => 'required|in:male,female',
    ]);

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
  public function updateEmail(Request $request)
  {
    $user = Auth::user();
    
    $request->validate([
      'email' => 'required|email|unique:roleaccounts,email,' . $user->id,
      'current_password' => 'required|string',
    ]);

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
  public function updatePassword(Request $request)
  {
    $user = Auth::user();
    
    $request->validateWithBag('updatePassword', [
      'current_password' => 'required|string',
      'password' => 'required|min:8|confirmed',
    ]);

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


