<?php

namespace App\Services;

use App\Models\GoodMoralApplication;
use App\Models\NotifArchive;
use App\Models\Receipt;
use App\Models\RoleAccount;
use App\Models\StudentViolation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CertificateService
{
    protected NotificationArchiveService $notifService;

    public function __construct(NotificationArchiveService $notifService)
    {
        $this->notifService = $notifService;
    }

    /**
     * Generate and return a certificate PDF for a GoodMoralApplication.
     *
     * @param int    $id               Application ID
     * @param string $action           'download' or 'stream'
     * @param array  $allowedStatuses  Statuses that allow printing
     * @param string $errorRedirect    Route to redirect on error
     * @param bool   $addReprintSuffix Whether to add _REPRINT to filename
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function generateCertificate(
        int $id,
        string $action = 'download',
        array $allowedStatuses = ['Ready for Moderator Print', 'Ready for Pickup'],
        string $errorRedirect = 'sec_osa.dashboard',
        bool $addReprintSuffix = false
    ) {
        try {
            Log::info("Certificate generation started for ID: {$id}");

            $application = GoodMoralApplication::findOrFail($id);

            // Check application status
            if (!in_array($application->application_status, $allowedStatuses)) {
                Log::warning("Application not in printable status", ['status' => $application->application_status]);
                return redirect()->route($errorRedirect)->with('error', 'Application is not ready for printing!');
            }

            // Check receipt
            $receipt = Receipt::where('reference_num', $application->reference_number)->first();
            if (!$receipt || !$receipt->document_path) {
                Log::error("Receipt not found", ['reference_number' => $application->reference_number]);
                return redirect()->route($errorRedirect)->with('error', 'Payment receipt not found!');
            }

            // Get student details
            $studentDetails = RoleAccount::where('student_id', $application->student_id)->first();
            if (!$studentDetails) {
                Log::error("Student details not found", ['student_id' => $application->student_id]);
                return redirect()->route($errorRedirect)->with('error', 'Student details not found!');
            }

            $currentUser = Auth::user();

            // Prepare PDF data
            $data = [
                'title' => $application->certificate_type === 'good_moral' ? 'Good Moral Certificate' : 'Certificate of Residency',
                'application' => $application,
                'receipt' => $receipt,
                'printed_by' => $currentUser->fullname,
                'studentDetails' => $studentDetails,
                'studentDetails1' => $application,
                'print_date' => now()->format('F j, Y'),
                'reasons_array' => $application->reasons_array,
                'number_of_copies' => (int) $application->number_of_copies,
            ];

            // Determine PDF view
            $view = $this->determineCertificateView($application, $studentDetails);

            if (!view()->exists($view)) {
                Log::error("View does not exist", ['view' => $view]);
                return redirect()->route($errorRedirect)->with('error', "PDF template '{$view}' not found!");
            }

            // Generate PDF
            $pdf = Pdf::loadView($view, $data);
            $pdf->setPaper('letter', 'portrait');

            // On first print, update status and create notification
            $isReprint = $application->application_status === 'Ready for Pickup';
            if ($application->application_status === 'Ready for Moderator Print') {
                $application->application_status = 'Ready for Pickup';
                $application->save();

                $this->notifService->createFromApplication($application, '5');
                Log::info("First print - status updated and notification created");
            }

            // Generate filename
            $certificateType = $application->certificate_type === 'good_moral' ? 'GoodMoral' : 'Residency';
            $reprintSuffix = ($addReprintSuffix && $isReprint) ? '_REPRINT' : '';
            $filename = "{$certificateType}_Certificate_{$application->student_id}_{$application->reference_number}{$reprintSuffix}.pdf";

            return $this->outputPdf($pdf, $filename, $action);

        } catch (\Exception $e) {
            Log::error("Certificate generation error", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->route($errorRedirect)->with('error', 'An error occurred while generating the certificate: ' . $e->getMessage());
        }
    }

    /**
     * Determine which PDF view template to use.
     */
    private function determineCertificateView(GoodMoralApplication $application, RoleAccount $studentDetails): string
    {
        $hasUnresolvedViolations = StudentViolation::where('student_id', $application->student_id)
            ->where('status', '!=', 2)
            ->exists();

        if ($application->certificate_type === 'good_moral') {
            return 'pdf.student_certificate';
        }

        if ($application->certificate_type === 'residency') {
            return $studentDetails->account_type === 'student'
                ? 'pdf.student_residency_certificate'
                : 'pdf.other_certificate';
        }

        // Fallback for legacy applications
        if ($hasUnresolvedViolations) {
            return $studentDetails->account_type === 'student'
                ? 'pdf.student_residency_certificate'
                : 'pdf.other_certificate';
        }

        return 'pdf.student_certificate';
    }

    /**
     * Output PDF via download or stream with fallback.
     */
    private function outputPdf($pdf, string $filename, string $action)
    {
        try {
            return $action === 'stream'
                ? $pdf->stream($filename)
                : $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error("PDF {$action} failed, trying fallback", ['error' => $e->getMessage()]);
            return $action === 'stream'
                ? $pdf->download($filename)
                : $pdf->stream($filename);
        }
    }
}
