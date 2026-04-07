<?php

namespace App\Traits;

use App\Models\StudentViolation;
use App\Models\ViolationNotif;
use App\Models\RoleAccount;

trait ViolationEscalationTrait
{
    /**
     * Check if a student has accumulated 3 minor violations and create major violation
     *
     * @param string $studentId
     * @return bool Returns true if escalation occurred
     */
    protected function checkMinorViolationEscalation($studentId)
    {
        // Count ALL minor violations for this student regardless of status (0, 1, 2)
        $minorViolationCount = StudentViolation::where('student_id', $studentId)
            ->where('offense_type', 'minor')
            ->count(); // Count all minor violations regardless of approval status

        // Check if student has exactly 3, 6, 9, etc. minor violations (multiples of 3)
        if ($minorViolationCount >= 3 && ($minorViolationCount % 3 == 0)) {
            // Check if we've already created a major violation for this set of 3 minor violations
            $existingEscalationCount = StudentViolation::where('student_id', $studentId)
                ->where('offense_type', 'major')
                ->where('violation', 'LIKE', '%Escalated from 3 minor violations%')
                ->count();

            // Only create a new major violation if we haven't already escalated this set
            $expectedEscalations = floor($minorViolationCount / 3);
            if ($existingEscalationCount < $expectedEscalations) {
                $this->createEscalatedMajorViolation($studentId, $minorViolationCount);
                $this->notifyAdminOfEscalation($studentId, $minorViolationCount);
                return true;
            }
        }

        return false;
    }

    /**
     * Create a major violation record from 3 minor violations
     *
     * @param string $studentId
     * @param int $minorViolationCount
     * @return StudentViolation
     */
    protected function createEscalatedMajorViolation($studentId, $minorViolationCount)
    {
        // Get student information
        $student = RoleAccount::where('student_id', $studentId)->first();

        if (!$student) {
            throw new \Exception("Student not found: $studentId");
        }

        // Generate unique case number
        $date = date('Ymd');
        do {
            $unique = strtoupper(\Illuminate\Support\Str::random(6));
            $caseNumber = "ESCALATION-{$date}-{$unique}";
            $exists = StudentViolation::where('ref_num', $caseNumber)->exists();
        } while ($exists);

        // Create the escalated major violation
        $majorViolation = StudentViolation::create([
            'first_name' => $student->fullname ? explode(' ', $student->fullname)[0] : 'Unknown',
            'last_name' => $student->fullname ? (explode(' ', $student->fullname)[1] ?? '') : 'Student',
            'student_id' => $studentId,
            'department' => $student->department ?? 'Unknown',
            'course' => $student->year_level ?? 'Unknown', // Using year_level as course info
            'offense_type' => 'major',
            'violation' => "Escalated from 3 minor violations - Accumulated Violations Threshold Reached",
            'ref_num' => $caseNumber,
            'added_by' => 'SYSTEM_ESCALATION',
            'status' => '0', // Pending status
            'unique_id' => uniqid(),
        ]);

        return $majorViolation;
    }

    /**
     * Notify admin that a student has accumulated 3 minor violations and major violation created
     *
     * @param string $studentId
     * @param int $violationCount
     */
    protected function notifyAdminOfEscalation($studentId, $violationCount)
    {
        // Get student information
        $student = RoleAccount::where('student_id', $studentId)->first();
        $studentName = $student ? $student->fullname : "Student ID: $studentId";

        // Get the latest escalated major violation for reference
        $majorViolation = StudentViolation::where('student_id', $studentId)
            ->where('offense_type', 'major')
            ->where('violation', 'LIKE', '%Escalated from 3 minor violations%')
            ->latest()
            ->first();

        $caseReference = $majorViolation ? $majorViolation->ref_num : 'ESCALATION-' . date('Ymd') . '-' . $studentId;

        // Get all admin users
        $admins = RoleAccount::where('account_type', 'admin')->get();

        foreach ($admins as $admin) {
            // Only create notification if admin has a student_id (some staff accounts might not have one)
            if ($admin->student_id) {
                ViolationNotif::create([
                    'ref_num' => $caseReference,
                    'student_id' => $admin->student_id, // Notify admin
                    'status' => 0, // Pending/unread
                    'notif' => "🚨 AUTOMATIC ESCALATION: Student {$studentName} ({$studentId}) has accumulated {$violationCount} minor violations. A major violation has been automatically created (Case: {$caseReference}). Immediate administrative action required.",
                ]);
            }
        }

        // Also create a notification for the student using new format
        $escalationMessage = generateHandbookReference('major') . ". 🚨 CRITICAL NOTICE: You have accumulated {$violationCount} minor violations. This has automatically resulted in a MAJOR VIOLATION (Case: {$caseReference}). Please go to the Dean's office immediately for compliance.";

        ViolationNotif::create([
            'ref_num' => $caseReference,
            'student_id' => $studentId,
            'status' => 0, // Pending/unread
            'notif' => $escalationMessage,
        ]);

        // Notify Program Coordinators of the student's department
        if ($student && $student->department) {
            $progCoordinators = RoleAccount::where('account_type', 'prog_coor')
                ->where('department', $student->department)
                ->get();

            foreach ($progCoordinators as $coordinator) {
                // Only create notification if coordinator has a student_id (some staff accounts might not have one)
                if ($coordinator->student_id) {
                    ViolationNotif::create([
                        'ref_num' => $caseReference,
                        'student_id' => $coordinator->student_id,
                        'status' => 0,
                        'notif' => "⚠️ ESCALATION ALERT: Student {$studentName} ({$studentId}) from your department has been escalated to a major violation due to accumulating 3 minor violations. Case: {$caseReference}.",
                    ]);
                }
            }
        }
    }

    /**
     * Get violation escalation summary for a student
     *
     * @param string $studentId
     * @return array
     */
    protected function getViolationEscalationSummary($studentId)
    {
        $minorViolations = StudentViolation::where('student_id', $studentId)
            ->where('offense_type', 'minor')
            ->count(); // Count ALL minor violations regardless of status

        $majorViolations = StudentViolation::where('student_id', $studentId)
            ->where('offense_type', 'major')
            ->whereIn('status', [0, 1, 1.5]) // Pending, in progress, or forwarded to admin (not fully resolved)
            ->count();

        // Count escalated major violations specifically
        $escalatedMajorViolations = StudentViolation::where('student_id', $studentId)
            ->where('offense_type', 'major')
            ->where('violation', 'LIKE', '%Escalated from 3 minor violations%')
            ->whereIn('status', [0, 1, 1.5])
            ->count();

        // Calculate equivalent major violations from minor violations
        $equivalentMajorFromMinor = floor($minorViolations / 3);
        $remainingMinor = $minorViolations % 3;

        return [
            'minor_violations' => $minorViolations,
            'major_violations' => $majorViolations,
            'escalated_major_violations' => $escalatedMajorViolations,
            'direct_major_violations' => $majorViolations - $escalatedMajorViolations,
            'equivalent_major_from_minor' => $equivalentMajorFromMinor,
            'remaining_minor' => $remainingMinor,
            'total_effective_major' => $majorViolations,
            'needs_escalation' => $minorViolations >= 3 && ($minorViolations % 3 == 0) && ($escalatedMajorViolations < $equivalentMajorFromMinor)
        ];
    }

    /**
     * Check if student is approaching escalation threshold
     *
     * @param string $studentId
     * @return array
     */
    protected function getEscalationWarning($studentId)
    {
        $minorViolations = StudentViolation::where('student_id', $studentId)
            ->where('offense_type', 'minor')
            ->count(); // Count ALL minor violations regardless of status

        $remainingUntilEscalation = 3 - ($minorViolations % 3);

        if ($remainingUntilEscalation == 3) {
            $remainingUntilEscalation = 0; // Already at escalation point
        }

        return [
            'current_minor_count' => $minorViolations,
            'violations_until_escalation' => $remainingUntilEscalation,
            'is_at_threshold' => $minorViolations > 0 && ($minorViolations % 3 == 0),
            'warning_level' => $this->getWarningLevel($minorViolations)
        ];
    }

    /**
     * Get warning level based on minor violation count
     *
     * @param int $count
     * @return string
     */
    private function getWarningLevel($count)
    {
        if ($count >= 3) {
            return 'critical'; // Red - escalation occurred
        } elseif ($count == 2) {
            return 'high'; // Orange - one away from escalation
        } elseif ($count == 1) {
            return 'medium'; // Yellow - first warning
        }

        return 'none'; // Green - no violations
    }

    /**
     * Get escalation status display for a student
     *
     * @param string $studentId
     * @return array
     */
    public function getEscalationStatusDisplay($studentId)
    {
        $summary = $this->getViolationEscalationSummary($studentId);
        $warning = $this->getEscalationWarning($studentId);

        $statusText = '';
        $statusColor = '';
        $statusIcon = '';

        switch ($warning['warning_level']) {
            case 'critical':
                $statusText = "⚠️ ESCALATED: {$summary['escalated_major_violations']} Major Violation(s) from Minor Violations";
                $statusColor = '#dc3545'; // Red
                $statusIcon = '🚨';
                break;
            case 'high':
                $statusText = "⚠️ WARNING: 1 more minor violation will result in major violation";
                $statusColor = '#fd7e14'; // Orange
                $statusIcon = '⚠️';
                break;
            case 'medium':
                $statusText = "⚠️ CAUTION: 2 more minor violations will result in major violation";
                $statusColor = '#ffc107'; // Yellow
                $statusIcon = '⚠️';
                break;
            default:
                $statusText = "✅ No escalation risk";
                $statusColor = '#28a745'; // Green
                $statusIcon = '✅';
                break;
        }

        return [
            'status_text' => $statusText,
            'status_color' => $statusColor,
            'status_icon' => $statusIcon,
            'warning_level' => $warning['warning_level'],
            'minor_count' => $summary['minor_violations'],
            'remaining_minor' => $summary['remaining_minor'],
            'escalated_major_count' => $summary['escalated_major_violations']
        ];
    }
}
