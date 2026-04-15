<?php

namespace App\Services;

use App\Models\GoodMoralApplication;
use App\Models\HeadOSAApplication;
use App\Models\Receipt;
use App\Models\SecOSAApplication;
use App\Models\DeanApplication;
use App\Services\ReceiptService;
use Illuminate\Support\Facades\Log;

class GoodMoralWorkflowService
{
    protected NotificationArchiveService $notifService;

    public function __construct(NotificationArchiveService $notifService)
    {
        $this->notifService = $notifService;
    }

    /**
     * Handle receipt upload for a GoodMoralApplication.
     * Links receipt to application, creates HeadOSAApplication for admin review.
     */
    public function handleReceiptUpload(GoodMoralApplication $application, Receipt $receipt): void
    {
        // Link receipt to application and advance workflow status
        $application->receipt_id = $receipt->id;
        $application->status = 'receipt_uploaded';
        $application->application_status = 'Receipt Uploaded - Pending Admin Approval';
        $application->save();

        // Create HeadOSAApplication so Head OSA / Admin can review
        HeadOSAApplication::create([
            'number_of_copies' => $application->number_of_copies,
            'reference_number' => $application->reference_number,
            'student_id' => $application->student_id,
            'department' => $application->department,
            'reason' => $application->formatted_reasons,
            'fullname' => $application->fullname,
            'course_completed' => $application->course_completed,
            'graduation_date' => $application->graduation_date,
            'is_undergraduate' => $application->is_undergraduate,
            'last_course_year_level' => $application->last_course_year_level,
            'last_semester_sy' => $application->last_semester_sy,
            'status' => 'pending',
        ]);

        // Notification: receipt uploaded, awaiting admin review
        $this->notifService->createFromApplication($application, '4');

        Log::info('Receipt uploaded and HeadOSAApplication created', [
            'reference_number' => $application->reference_number,
            'receipt_id' => $receipt->id,
        ]);
    }

    /**
     * Approve a GoodMoralApplication at the admin level (new system).
     * Sets status to approved and application ready for printing.
     */
    public function approveByAdmin(GoodMoralApplication $application): void
    {
        $application->status = 'approved';
        $application->application_status = 'Ready for Moderator Print';
        $application->save();

        // Also approve the HeadOSAApplication if it exists
        $headOsaApp = HeadOSAApplication::where('reference_number', $application->reference_number)
            ->where('status', 'pending')
            ->first();
        if ($headOsaApp) {
            $headOsaApp->status = 'approved';
            $headOsaApp->save();
        }

        // Notification: admin approved, ready for printing
        $this->notifService->createFromApplication($application, '2');
    }

    /**
     * Reject a GoodMoralApplication at the admin level (new system).
     */
    public function rejectByAdmin(GoodMoralApplication $application, ?string $reason = null, ?string $details = null): void
    {
        $application->status = 'rejected';
        $application->application_status = 'Rejected by Administrator';
        $application->rejection_reason = $reason;
        $application->rejection_details = $details;
        $application->rejected_by = 'Administrator';
        $application->rejected_at = now();
        $application->action_history = ($application->action_history ?? '') . "\n" . now()->format('Y-m-d H:i:s') . " - Rejected by Administrator (Reason: {$reason})";
        $application->save();

        // Also reject the HeadOSAApplication if it exists
        $headOsaApp = HeadOSAApplication::where('reference_number', $application->reference_number)
            ->where('status', 'pending')
            ->first();
        if ($headOsaApp) {
            $headOsaApp->status = 'rejected';
            $headOsaApp->save();
        }

        $this->notifService->createFromApplication($application, '-2');
    }

    /**
     * Approve a HeadOSAApplication (legacy → SecOSA pipeline).
     * Creates SecOSAApplication record and notification.
     */
    public function approveLegacyByAdmin(HeadOSAApplication $headOsaApp): void
    {
        $headOsaApp->status = 'approved';
        $headOsaApp->save();

        $student = $headOsaApp->student;
        $goodMoralApp = GoodMoralApplication::where('student_id', $headOsaApp->student_id)->first();

        if ($goodMoralApp) {
            $goodMoralApp->status = 'approved';
            $goodMoralApp->application_status = 'Ready for Moderator Print';
            $goodMoralApp->save();
        }

        if ($student) {
            SecOSAApplication::firstOrCreate(
                ['reference_number' => $headOsaApp->reference_number],
                [
                    'number_of_copies' => $headOsaApp->number_of_copies,
                    'student_id' => $student->student_id,
                    'fullname' => $student->fullname,
                    'department' => $student->department,
                    'reason' => $headOsaApp->reason,
                    'course_completed' => $headOsaApp->course_completed,
                    'graduation_date' => $headOsaApp->graduation_date,
                    'is_undergraduate' => $headOsaApp->is_undergraduate,
                    'last_course_year_level' => $headOsaApp->last_course_year_level,
                    'last_semester_sy' => $headOsaApp->last_semester_sy,
                    'status' => 'pending',
                ]
            );
        }

        $this->notifService->createFromLegacyApplication($headOsaApp, '2', $goodMoralApp);
    }

    /**
     * Reject a HeadOSAApplication (legacy).
     */
    public function rejectLegacyByAdmin(HeadOSAApplication $headOsaApp): void
    {
        $headOsaApp->status = 'rejected';
        $headOsaApp->save();

        $goodMoralApp = GoodMoralApplication::where('student_id', $headOsaApp->student_id)->first();
        if ($goodMoralApp) {
            $goodMoralApp->status = 'rejected';
            $goodMoralApp->application_status = 'Rejected by Administrator';
            $goodMoralApp->save();
        }

        $this->notifService->createFromLegacyApplication($headOsaApp, '-2', $goodMoralApp);
    }

    /**
     * Dean approves a GoodMoralApplication.
     * Sets status to waiting_for_payment and generates payment notice.
     */
    public function approveByDean(GoodMoralApplication $application, string $deanFullname): array
    {
        $application->status = 'waiting_for_payment';
        $application->application_status = "Approved by Dean: {$deanFullname} - Waiting for Payment";
        $application->save();

        // Generate payment notice so student can pay at Business Affairs
        $receiptService = new ReceiptService();
        $receiptData = $receiptService->generatePaymentNotice($application);

        $this->notifService->createFromApplication($application, '3');

        return [
            'receipt_number' => $receiptData['receipt_number'] ?? null,
            'formatted_payment' => $application->formatted_payment,
        ];
    }

    /**
     * Dean rejects a GoodMoralApplication.
     */
    public function rejectByDean(GoodMoralApplication $application, string $deanFullname, ?string $reason = null, ?string $details = null): void
    {
        $application->application_status = "Rejected by Dean: {$deanFullname}";

        if ($reason) {
            $application->status = 'rejected';
            $application->rejection_reason = $reason;
            $application->rejection_details = $details;
            $application->rejected_by = "Dean: {$deanFullname}";
            $application->rejected_at = now();
            $application->action_history = ($application->action_history ?? '') . "\n" . now()->format('Y-m-d H:i:s') . " - Rejected by Dean: {$deanFullname} (Reason: {$reason})";
        }

        $application->save();

        $applicationStatus = $reason ? "Rejected by Dean: {$reason}" : null;
        $this->notifService->createFromApplication($application, '-3', $applicationStatus);
    }
}
