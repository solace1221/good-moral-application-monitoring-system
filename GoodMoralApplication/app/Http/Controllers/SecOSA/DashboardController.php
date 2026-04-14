<?php

namespace App\Http\Controllers\SecOSA;

use App\Http\Controllers\Controller;
use App\Models\GoodMoralApplication;
use App\Models\SecOSAApplication;
use App\Models\StudentViolation;
use App\Models\ViolationNotif;
use App\Services\DashboardStatsService;
use App\Services\TrendsAnalysisService;
use App\Traits\RoleCheck;

class DashboardController extends Controller
{
    use RoleCheck;

    protected DashboardStatsService $statsService;
    protected TrendsAnalysisService $trendsService;

    public function __construct(DashboardStatsService $statsService, TrendsAnalysisService $trendsService)
    {
        $this->statsService = $statsService;
        $this->trendsService = $trendsService;
        $this->checkRole(['sec_osa']);
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

        // Use DashboardStatsService for stats
        $deptCounts = $this->statsService->getApplicationCountsByDepartment($frequency);
        $site = $deptCounts['SITE'];
        $saste = $deptCounts['SASTE'];
        $sbahm = $deptCounts['SBAHM'];
        $snahs = $deptCounts['SNAHS'];
        $som = $deptCounts['SOM'];
        $gradsch = $deptCounts['GRADSCH'];

        $vStats = $this->statsService->getViolationStats($frequency);
        $minorPending = $vStats['minorPending'];
        $minorResolved = $vStats['minorResolved'];
        $minorTotal = $vStats['minorTotal'];
        $minorResolvedPercentage = $vStats['minorResolvedPercentage'];
        $majorPending = $vStats['majorPending'];
        $majorResolved = $vStats['majorResolved'];
        $majorTotal = $vStats['majorTotal'];
        $majorResolvedPercentage = $vStats['majorResolvedPercentage'];

        $deptViolations = $this->statsService->getViolationsByDepartment($frequency);
        $majorViolationsByDept = $deptViolations['majorViolationsByDept'];
        $minorViolationsByDept = $deptViolations['minorViolationsByDept'];

        $applications = SecOSAApplication::where('status', 'pending')->get();

        // Fetch applications ready for printing and already printed (from admin approval with receipt uploaded)
        $printApplications = GoodMoralApplication::readyForPrint()
            ->orderBy('updated_at', 'desc')
            ->get();

        // Get escalation notifications (students with 3+ minor violations)
        $escalationNotifications = ViolationNotif::where('ref_num', 'LIKE', 'ESCALATION-%')
            ->where('student_id', 'ROLE_ADMIN')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get trends analysis data for overall reports
        $trendsData = $this->trendsService->getMajorOffenseTrendsData(false);
        $minorOffensesData = $this->trendsService->getMinorOffenseTrendsData(false);

        return view('sec_osa.dashboard', compact(
            'applications', 'printApplications', 'site', 'sbahm', 'saste', 'snahs', 'som', 'gradsch',
            'minorPending', 'minorResolved', 'minorTotal', 'minorResolvedPercentage',
            'majorPending', 'majorResolved', 'majorTotal', 'majorResolvedPercentage',
            'majorViolationsByDept', 'minorViolationsByDept', 'escalationNotifications',
            'frequency', 'frequencyOptions', 'frequencyLabel', 'trendsData', 'minorOffensesData'
        ));
    }
}
