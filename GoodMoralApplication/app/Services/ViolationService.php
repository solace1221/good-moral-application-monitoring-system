<?php

namespace App\Services;

use App\Models\StudentViolation;
use App\Models\Violation;
use App\Models\ViolationNotif;
use App\Models\RoleAccount;
use Illuminate\Support\Str;

class ViolationService
{
    /**
     * Get escalation status display data for a given student.
     */
    public function getEscalationStatusForStudent(string $studentId): array
    {
        $minorCount = StudentViolation::where('student_id', $studentId)
            ->where('offense_type', 'minor')
            ->count();

        $statusColor = '#28a745'; // Green
        $statusIcon = '✅';
        if ($minorCount >= 3) {
            $statusColor = '#dc3545'; // Red
            $statusIcon = '🚨';
        } elseif ($minorCount == 2) {
            $statusColor = '#fd7e14'; // Orange
            $statusIcon = '⚠️';
        } elseif ($minorCount >= 1) {
            $statusColor = '#ffc107'; // Yellow
            $statusIcon = '⚠️';
        }

        return [
            'status_color' => $statusColor,
            'status_icon' => $statusIcon,
            'minor_count' => $minorCount,
            'warning_level' => $minorCount >= 3 ? 'critical' : ($minorCount == 2 ? 'high' : ($minorCount == 1 ? 'medium' : 'none')),
        ];
    }

    /**
     * Get escalation data for all students with minor violations.
     */
    public function getAllEscalationData(): array
    {
        $escalationData = [];
        $students = StudentViolation::where('offense_type', 'minor')
            ->select('student_id')
            ->distinct()
            ->get();

        foreach ($students as $student) {
            $escalationData[$student->student_id] = $this->getEscalationStatusForStudent($student->student_id);
        }

        return $escalationData;
    }

    /**
     * Create a single violation record with notification.
     */
    public function createViolation(array $data, string $addedBy): StudentViolation
    {
        $violation = StudentViolation::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'student_id' => $data['student_id'],
            'department' => $data['department'],
            'course' => $data['course'],
            'offense_type' => $data['offense_type'],
            'violation' => $data['violation'],
            'ref_num' => $data['ref_num'] ?? null,
            'added_by' => $addedBy,
            'status' => '0',
            'unique_id' => uniqid(),
        ]);

        $this->createViolationNotification($violation);

        return $violation;
    }

    /**
     * Create a violation notification for a given StudentViolation.
     */
    public function createViolationNotification(StudentViolation $violation): void
    {
        $violationRecord = Violation::where('description', $violation->violation)->first();
        $article = $violationRecord?->article;

        ViolationNotif::create([
            'ref_num' => $violation->ref_num ?? 'VIOLATION-' . $violation->id,
            'student_id' => $violation->student_id,
            'status' => 0,
            'notif' => generateViolationNotification($violation->offense_type, $violation->getAttribute('violation'), $article, $violation->added_by),
        ]);
    }

    /**
     * Build escalation notifications list for admin/moderator views.
     */
    public function getEscalationNotificationsList(): array
    {
        $escalatedStudents = StudentViolation::selectRaw('student_id, COUNT(*) as minor_violation_count')
            ->where('offense_type', 'minor')
            ->groupBy('student_id')
            ->havingRaw('COUNT(*) >= 3')
            ->orderByRaw('COUNT(*) DESC')
            ->get();

        $notifications = [];
        foreach ($escalatedStudents as $student) {
            $minorViolations = StudentViolation::where('student_id', $student->student_id)
                ->where('offense_type', 'minor')
                ->orderBy('created_at', 'desc')
                ->get();

            $latestRecord = $minorViolations->first();

            $autoMajorViolation = StudentViolation::where('student_id', $student->student_id)
                ->where('offense_type', 'major')
                ->where('violation', 'LIKE', '%Escalated from 3 minor violations%')
                ->first();

            $notifications[] = [
                'student_id' => $student->student_id,
                'fullname' => $latestRecord ? ($latestRecord->first_name . ' ' . $latestRecord->last_name) : $student->student_id,
                'department' => $latestRecord->department ?? null,
                'course' => null,
                'minor_violation_count' => $student->minor_violation_count,
                'minor_violations' => $minorViolations,
                'auto_major_violation' => $autoMajorViolation,
                'escalation_status' => $autoMajorViolation ? 'escalated' : 'pending_escalation',
                'latest_violation_date' => $minorViolations->first()->created_at ?? null,
            ];
        }

        usort($notifications, function ($a, $b) {
            if (!$a['latest_violation_date'] || !$b['latest_violation_date']) {
                return 0;
            }
            return $b['latest_violation_date']->timestamp - $a['latest_violation_date']->timestamp;
        });

        return $notifications;
    }

    /**
     * Generate a unique case number with the given prefix.
     * Replaces duplicated case-number generation across Dean, SecOSA, and Admin controllers.
     */
    public static function generateCaseNumber(string $prefix = 'CASE'): string
    {
        $date = date('Ymd');
        do {
            $unique = strtoupper(Str::random(6));
            $caseNumber = "{$prefix}-{$date}-{$unique}";
            $exists = StudentViolation::where('ref_num', $caseNumber)->exists();
        } while ($exists);

        return $caseNumber;
    }

    /**
     * Get violation status counts for major violations.
     * Used by SecOSA\ViolationController::major() / searchMajor().
     */
    public function getMajorStatusCounts(?array $departments = null): array
    {
        $query = StudentViolation::major();
        if ($departments) {
            $query->whereIn('department', $departments);
        }

        return [
            'pendingCount' => (clone $query)->where('status', '0')->count(),
            'proceedingsUploadedCount' => (clone $query)->where('status', '1')->count(),
            'forwardedCount' => (clone $query)->where('status', '1.5')->count(),
            'closedCount' => (clone $query)->where('status', '2')->count(),
        ];
    }

    /**
     * Get violation status counts for minor violations.
     * Used by SecOSA\ViolationController::minor() / searchMinor().
     */
    public function getMinorStatusCounts(?array $departments = null): array
    {
        $query = StudentViolation::minor();
        if ($departments) {
            $query->whereIn('department', $departments);
        }

        return [
            'pendingCount' => (clone $query)->where('status', '0')->count(),
            'approvedCount' => (clone $query)->where('status', '1')->count(),
            'closedCount' => (clone $query)->where('status', '2')->count(),
        ];
    }
}
