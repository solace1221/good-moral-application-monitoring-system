<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodMoralApplication;
use App\Models\HeadOSAApplication;
use App\Services\CertificateService;
use App\Services\GoodMoralWorkflowService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
  protected CertificateService $certificateService;
  protected GoodMoralWorkflowService $workflowService;

  public function __construct(
    CertificateService $certificateService,
    GoodMoralWorkflowService $workflowService
  ) {
    $this->certificateService = $certificateService;
    $this->workflowService = $workflowService;
  }

  public function applicationDashboard()
  {
    // Only show applications that have been approved by the dean
    $applications = GoodMoralApplication::where('application_status', 'LIKE', 'Approved by Dean:%')
      ->whereNotIn('application_status', ['Approved by Administrator', 'Rejected by Administrator'])
      ->get();

    return view('admin.application', compact('applications'));
  }

  public function approveGMA($id)
  {
    $application = HeadOSAApplication::findOrFail($id);

    if (!$application->student) {
      return redirect()->route('admin.GMAApporvedByRegistrar')->with('error', 'Student not found.');
    }

    $this->workflowService->approveLegacyByAdmin($application);

    return redirect()->route('admin.GMAApporvedByRegistrar')->with(
      'status',
      'Application approved and ready to print'
    );
  }

  public function rejectGMA($id)
  {
    $application = HeadOSAApplication::findOrFail($id);
    $this->workflowService->rejectLegacyByAdmin($application);
    return redirect()->route('admin.GMAApporvedByRegistrar')->with('status', 'Application rejected!');
  }

  /**
   * Approve a Good Moral Application (new system).
   */
  public function approveGoodMoralApplication($id)
  {
    $application = GoodMoralApplication::findOrFail($id);

    if (!str_contains($application->application_status, 'Approved by Dean:')) {
      return redirect()->route('admin.GMAApporvedByRegistrar')->with('error', 'Application must be approved by Dean first.');
    }

    $result = $this->workflowService->approveByAdmin($application);

    return redirect()->route('admin.GMAApporvedByRegistrar')->with('status', "Application approved! Payment notice {$result['receipt_number']} has been generated for {$result['formatted_payment']}. The student will be notified to upload the receipt.");
  }

  /**
   * Reject a Good Moral Application (new system).
   */
  public function rejectGoodMoralApplication($id)
  {
    $application = GoodMoralApplication::findOrFail($id);

    if (!str_contains($application->application_status, 'Approved by Dean:')) {
      return redirect()->route('admin.GMAApporvedByRegistrar')->with('error', 'Application must be approved by Dean first.');
    }

    $this->workflowService->rejectByAdmin($application);

    return redirect()->route('admin.GMAApporvedByRegistrar')->with('status', 'Application rejected successfully!');
  }

  public function readyForPrintApplications(Request $request)
  {
    // Get base query for applications that have been approved by admin
    $baseQuery = GoodMoralApplication::whereIn('application_status', [
      'Approved by Administrator',
      'Ready for Moderator Print',
      'Ready for Pickup'
    ])->orderBy('updated_at', 'desc');

    // Get all approved applications (don't filter by receipt - show all approved)
    $allApplicationsQuery = clone $baseQuery;
    $allApplications = $allApplicationsQuery->get();

    // Get paginated applications for each type
    $perPage = 10; // Number of items per page
    $currentPage = $request->get('page', 1);

    // For "All Applications" tab
    $allApplicationsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
      $allApplications->forPage($currentPage, $perPage),
      $allApplications->count(),
      $perPage,
      $currentPage,
      ['path' => $request->url(), 'pageName' => 'page']
    );

    // For "Good Moral" tab
    $goodMoralApplications = $allApplications->where('certificate_type', 'good_moral');
    $goodMoralPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
      $goodMoralApplications->forPage($currentPage, $perPage),
      $goodMoralApplications->count(),
      $perPage,
      $currentPage,
      ['path' => $request->url(), 'pageName' => 'page']
    );

    // For "Residency" tab
    $residencyApplications = $allApplications->where('certificate_type', 'residency');
    $residencyPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
      $residencyApplications->forPage($currentPage, $perPage),
      $residencyApplications->count(),
      $perPage,
      $currentPage,
      ['path' => $request->url(), 'pageName' => 'page']
    );

    // Organize applications by type with pagination
    $applications = [
      'all_good_moral' => $allApplicationsPaginated,
      'good_moral' => $goodMoralPaginated,
      'residency' => $residencyPaginated,
    ];

    return view('admin.ready-for-print-applications', compact('applications'));
  }

  /**
   * Print certificate for applications with uploaded receipts
   */
  public function printCertificate($id)
  {
    return $this->certificateService->generateCertificate(
      $id,
      'download',
      ['Approved by Administrator', 'Ready for Moderator Print', 'Ready for Pickup'],
      'admin.readyForPrintApplications',
      true
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
      ['Ready for Pickup', 'Approved by Administrator', 'Ready for Moderator Print'],
      'admin.readyForPrintApplications',
      false
    );
  }

  public function approveApplication($id)
  {
    $application = GoodMoralApplication::findOrFail($id);

    if (!str_contains($application->application_status, 'Approved by Dean:')) {
      return redirect()->route('admin.Application')->with('error', 'Application must be approved by Dean first!');
    }

    if (str_contains($application->application_status, 'Approved by Administrator') ||
        str_contains($application->application_status, 'Rejected by Administrator')) {
      return redirect()->route('admin.Application')->with('error', 'Application has already been processed!');
    }

    $result = $this->workflowService->approveByAdmin($application);

    return redirect()->route('admin.Application')->with('status', "Application approved successfully! Payment notice {$result['receipt_number']} has been generated for {$result['formatted_payment']}. The student will be notified to upload the receipt.");
  }

  public function rejectApplication($id)
  {
    $application = GoodMoralApplication::findOrFail($id);

    if (!str_contains($application->application_status, 'Approved by Dean:')) {
      return redirect()->route('admin.Application')->with('error', 'Application must be approved by Dean first!');
    }

    if (str_contains($application->application_status, 'Approved by Administrator') ||
        str_contains($application->application_status, 'Rejected by Administrator')) {
      return redirect()->route('admin.Application')->with('error', 'Application has already been processed!');
    }

    $this->workflowService->rejectByAdmin($application);

    return redirect()->route('admin.Application')->with('status', 'Application rejected successfully! Student has been notified.');
  }

  public function search(Request $request)
  {
    // Start with dean-approved applications only
    $query = GoodMoralApplication::where('application_status', 'LIKE', 'Approved by Dean:%')
      ->whereNotIn('application_status', ['Approved by Administrator', 'Rejected by Administrator']);

    if ($request->filled('department')) {
      $query->where('department', 'like', '%' . $request->department . '%');
    }
    if ($request->filled('student_id')) {
      $query->where('student_id', 'like', '%' . $request->student_id . '%');
    }
    if ($request->filled('fullname')) {
      $query->where('fullname', 'like', '%' . $request->fullname . '%');
    }
    $applications = $query->paginate(10); // Get paginated results
    return view('admin.application', compact('applications'));
  }

  public function GMAApporvedByRegistrar()
  {
    // Fetch legacy applications (HeadOSAApplication)
    $legacyApplications = HeadOSAApplication::where('status', 'pending')->get();

    // Fetch applications that have been approved by the dean
    $deanApprovedApplications = GoodMoralApplication::where('application_status', 'like', 'Approved by Dean:%')
      ->orderBy('updated_at', 'desc')
      ->get();

    // Separate by certificate type
    $goodMoralApplications = $deanApprovedApplications->where('certificate_type', 'good_moral');
    $residencyApplications = $deanApprovedApplications->where('certificate_type', 'residency');

    // Organize applications by type
    $applications = [
      'legacy' => $legacyApplications,
      'good_moral' => $goodMoralApplications,
      'residency' => $residencyApplications,
      'all_dean_approved' => $deanApprovedApplications
    ];

    return view('admin.gma-approved-by-registrar', compact('applications'));
  }
}
