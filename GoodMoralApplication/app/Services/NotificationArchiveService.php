<?php

namespace App\Services;

use App\Models\NotifArchive;
use App\Models\GoodMoralApplication;

class NotificationArchiveService
{
    /**
     * Create a NotifArchive record from a GoodMoralApplication.
     */
    public function createFromApplication(GoodMoralApplication $application, string $status, ?string $applicationStatus = null): NotifArchive
    {
        return NotifArchive::create([
            'number_of_copies' => $application->number_of_copies,
            'reference_number' => $application->reference_number,
            'fullname' => $application->fullname,
            'gender' => $application->gender,
            'reason' => $application->reason,
            'student_id' => $application->student_id,
            'department' => $application->department,
            'course_completed' => $application->course_completed,
            'graduation_date' => $application->graduation_date,
            'application_status' => $applicationStatus,
            'is_undergraduate' => $application->is_undergraduate,
            'last_course_year_level' => $application->last_course_year_level,
            'last_semester_sy' => $application->last_semester_sy,
            'certificate_type' => $application->certificate_type,
            'status' => $status,
        ]);
    }

    /**
     * Create a NotifArchive from a legacy application (HeadOSAApplication/DeanApplication)
     * with explicit student info and a related GoodMoralApplication for extra fields.
     */
    public function createFromLegacyApplication($application, string $status, ?GoodMoralApplication $goodMoralApp = null, ?string $applicationStatus = null): NotifArchive
    {
        return NotifArchive::create([
            'number_of_copies' => $application->number_of_copies,
            'reference_number' => $application->reference_number,
            'fullname' => $application->fullname,
            'gender' => $goodMoralApp->gender ?? null,
            'reason' => $application->reason,
            'student_id' => $goodMoralApp->student_id ?? $application->student_id,
            'department' => $goodMoralApp->department ?? $application->department,
            'course_completed' => $application->course_completed,
            'graduation_date' => $application->graduation_date,
            'application_status' => $applicationStatus,
            'is_undergraduate' => $application->is_undergraduate,
            'last_course_year_level' => $application->last_course_year_level,
            'last_semester_sy' => $application->last_semester_sy,
            'certificate_type' => $goodMoralApp->certificate_type ?? 'good_moral',
            'status' => $status,
        ]);
    }

    /**
     * Create a NotifArchive from raw data (used when applying for certificate).
     */
    public function createFromData(array $data): NotifArchive
    {
        return NotifArchive::create($data);
    }
}
