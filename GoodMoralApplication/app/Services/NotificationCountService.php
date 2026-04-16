<?php

namespace App\Services;

use App\Models\GoodMoralApplication;
use App\Models\StudentOfficerApplication;
use App\Models\RoleAccount;
use App\Models\StudentViolation;

class NotificationCountService
{
    /**
     * Get notification counts for admin sidebar.
     */
    public function getAdminCounts(): array
    {
        return [
            'pendingApplications' => GoodMoralApplication::where('application_status', 'LIKE', 'Approved by Dean:%')
                ->whereNotIn('application_status', ['Approved by Administrator', 'Rejected by Administrator'])
                ->count(),
            'psgApplications' => StudentOfficerApplication::where('status', 'pending')->count(),
            'pendingViolations' => StudentViolation::whereNotNull('forwarded_to_admin_at')
                ->whereNotIn('status', ['2', 'Closed', 'Complied'])
                ->count(),
            'escalationNotifications' => $this->getEscalationCount(),
        ];
    }

    /**
     * Get notification counts for SecOSA/moderator navbar.
     */
    public function getSecOSACounts(): array
    {
        return [
            'pendingMinorViolations' => StudentViolation::minor()->where('status', 0)->count(),
            'pendingMajorViolations' => StudentViolation::major()->where('status', 0)->count(),
            'escalationNotifications' => $this->getEscalationCount(),
            'printReadyApplications' => GoodMoralApplication::where('application_status', 'Ready for Moderator Print')->count(),
        ];
    }

    /**
     * Get notification counts for dean sidebar.
     */
    public function getDeanCounts(string $department): array
    {
        $possibleDepartments = DashboardStatsService::getPossibleDepartmentNames($department);

        return [
            'pendingApplications' => GoodMoralApplication::approvedByRegistrar()
                ->where('department', $department)
                ->whereNotNull('application_status')
                ->count(),
            'majorViolations' => StudentViolation::major()
                ->whereIn('department', $possibleDepartments)
                ->where('status', 0)
                ->count(),
            'minorViolations' => StudentViolation::minor()
                ->whereIn('department', $possibleDepartments)
                ->where('status', 0)
                ->count(),
        ];
    }

    /**
     * Get notification counts for registrar sidebar.
     */
    public function getRegistrarCounts(): array
    {
        return [
            'pendingApplications' => GoodMoralApplication::where('status', 'pending')->count(),
        ];
    }

    /**
     * Get count of students with 3+ minor violations (escalation threshold).
     */
    public function getEscalationCount(): int
    {
        return StudentViolation::select('student_id')
            ->where('offense_type', 'minor')
            ->whereNotIn('status', ['2', 'Closed', 'Complied'])
            ->groupBy('student_id')
            ->havingRaw('COUNT(*) >= 3')
            ->count();
    }
}
