<?php

namespace App\Services;

use App\Models\GoodMoralApplication;
use App\Models\HeadOSAApplication;
use App\Models\SecOSAApplication;
use App\Models\DeanApplication;
use App\Services\ReceiptService;

class GoodMoralWorkflowService
{
    protected NotificationArchiveService $notifService;

    public function __construct(NotificationArchiveService $notifService)
    {
        $this->notifService = $notifService;
    }

    /**
     * Approve a GoodMoralApplication at the admin level (new system).
     * Updates status, generates payment notice, creates notification.
     */
    public function approveByAdmin(GoodMoralApplication $application): array
    {
        $application->application_status = 'Approved by Administrator';
        $application->save();

        $receiptService = new ReceiptService();
        $receiptData = $receiptService->generatePaymentNotice($application);

        $this->notifService->createFromApplication($application, '2');

        return [
            'receipt_number' => $receiptData['receipt_number'] ?? null,
            'formatted_payment' => $application->formatted_payment,
        ];
    }

    /**
     * Reject a GoodMoralApplication at the admin level (new system).
     */
    public function rejectByAdmin(GoodMoralApplication $application): void
    {
        $application->application_status = 'Rejected by Administrator';
        $application->save();

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
            $goodMoralApp->application_status = 'Approve by Administrator';
            $goodMoralApp->save();
        }

        if ($student) {
            SecOSAApplication::create([
                'number_of_copies' => $headOsaApp->number_of_copies,
                'reference_number' => $headOsaApp->reference_number,
                'student_id' => $student->student_id,
                'fullname' => $student->fullname,
                'department' => $student->department,
                'reason' => $headOsaApp->formatted_reasons,
                'course_completed' => $headOsaApp->course_completed,
                'graduation_date' => $headOsaApp->graduation_date,
                'is_undergraduate' => $headOsaApp->is_undergraduate,
                'last_course_year_level' => $headOsaApp->last_course_year_level,
                'last_semester_sy' => $headOsaApp->last_semester_sy,
                'status' => 'pending',
            ]);
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
            $goodMoralApp->application_status = 'Rejected by Administrator';
            $goodMoralApp->save();
        }

        $this->notifService->createFromLegacyApplication($headOsaApp, '-2', $goodMoralApp);
    }

    /**
     * Dean approves a GoodMoralApplication.
     */
    public function approveByDean(GoodMoralApplication $application, string $deanFullname): void
    {
        $application->application_status = "Approved by Dean: {$deanFullname}";
        $application->save();

        $this->notifService->createFromApplication($application, '3');
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
