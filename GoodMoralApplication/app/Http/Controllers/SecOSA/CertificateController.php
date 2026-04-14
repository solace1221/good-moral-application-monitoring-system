<?php

namespace App\Http\Controllers\SecOSA;

use App\Http\Controllers\Controller;
use App\Models\GoodMoralApplication;
use App\Models\SecOSAApplication;
use App\Models\StudentRegistration;
use App\Services\CertificateService;
use App\Traits\RoleCheck;
use App\Services\NotificationArchiveService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    use RoleCheck;

    protected CertificateService $certificateService;
    protected NotificationArchiveService $notifService;

    public function __construct(CertificateService $certificateService, NotificationArchiveService $notifService)
    {
        $this->certificateService = $certificateService;
        $this->notifService = $notifService;
        $this->checkRole(['admin', 'sec_osa']);
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
            'claimed' => $goodMoralApplications->where('application_status', 'Claimed'),
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

        return view('sec_osa.application', compact('applications'));
    }

    // TODO: Legacy method - review later
    public function approve(Request $request, $id)
    {
        try {
            $application = SecOSAApplication::findOrFail($id);
            $studentDetails = StudentRegistration::where('student_id', $application->student_id)->first();
            $studentDetails1 = GoodMoralApplication::where('reference_number', $application->reference_number)->first();

            $application->status = 'approved';
            $application->save();

            $sec_osa = Auth::user();

            $data = [
                'title' => 'Application Approved',
                'application' => $application,
                'approved_by' => $sec_osa->fullname,
                'studentDetails' => $studentDetails,
                'studentDetails1' => $studentDetails1,
            ];

            $view = ($studentDetails->account_type === 'student')
                ? 'pdf.student_certificate'
                : 'pdf.other_certificate';

            $pdf = Pdf::loadView($view, $data);
            Log::info('PDF generated successfully.');

            Storage::makeDirectory('public/pdfs');

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

    // TODO: Legacy method - review later
    public function reject($id)
    {
        $application = SecOSAApplication::findOrFail($id);
        $application->status = 'rejected';
        $application->save();

        return redirect()->route('sec_osa.dashboard')->with('status', 'Application rejected!');
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
     * Mark a certificate as claimed by the student.
     */
    public function markAsClaimed($id)
    {
        $application = GoodMoralApplication::findOrFail($id);

        if ($application->application_status !== 'Ready for Pickup') {
            return redirect()->route('sec_osa.application')->with('error', 'Only printed certificates can be marked as claimed.');
        }

        $application->application_status = 'Claimed';
        $application->claimed_at = now();
        $application->claimed_by = Auth::id();
        $application->save();

        $this->notifService->createFromApplication($application, '6');

        return redirect()->route('sec_osa.application')->with('status', 'Certificate marked as claimed successfully.');
    }
}
