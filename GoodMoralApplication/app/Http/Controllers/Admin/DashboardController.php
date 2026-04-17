<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardStatsService;
use App\Services\TrendsAnalysisService;
use App\Models\ViolationNotif;
use App\Models\Violation;
use App\Traits\DateFilterTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
  use DateFilterTrait;

  protected DashboardStatsService $statsService;
  protected TrendsAnalysisService $trendsService;

  public function __construct(
    DashboardStatsService $statsService,
    TrendsAnalysisService $trendsService
  ) {
    $this->statsService = $statsService;
    $this->trendsService = $trendsService;
  }

  public function dashboard(Request $request)
  {
    // Check if user is authenticated
    if (!Auth::check()) {
      return redirect()->route('login');
    }

    // User authentication already verified by login system

    // Get frequency filter from request
    $frequency = $request->get('frequency', 'all');

    // Use DashboardStatsService for stats
    $deptCounts = $this->statsService->getApplicationCountsByDepartment($frequency);

    $vStats = $this->statsService->getViolationStats($frequency);
    $minorpending = $vStats['minorPending'];
    $minorcomplied = $vStats['minorResolved'];
    $majorpending = $vStats['majorPending'];
    $majorcomplied = $vStats['majorResolved'];

    // Percentages for minor offenses
    $totalMinor = $vStats['minorTotal'];
    $pendingPercent = $totalMinor > 0 ? ($minorpending / $totalMinor) * 100 : 0;
    $compliedPercent = 100 - $pendingPercent;
    $dashArray = $pendingPercent . ' ' . $compliedPercent;

    // Percentages for major offenses
    $totalMajor = $vStats['majorTotal'];
    $majorPendingPercent = $totalMajor > 0 ? ($majorpending / $totalMajor) * 100 : 0;
    $majorCompliedPercent = 100 - $majorPendingPercent;
    $majorDashArray = $majorPendingPercent . ' ' . $majorCompliedPercent;

    // Departments array for looping
    $departments = DashboardStatsService::getDepartments();

    // Violation counts by department
    $deptViolations = $this->statsService->getViolationsByDepartment($frequency);
    $majorCounts = $deptViolations['majorViolationsByDept'];
    $minorCounts = $deptViolations['minorViolationsByDept'];
    $majorViolationsByDept = $majorCounts;
    $minorViolationsByDept = $minorCounts;

    // Totals and percentages
    $minorTotal = $vStats['minorTotal'];
    $majorTotal = $vStats['majorTotal'];
    $minorResolved = $vStats['minorResolved'];
    $minorPending = $vStats['minorPending'];
    $majorResolved = $vStats['majorResolved'];
    $majorPending = $vStats['majorPending'];
    $minorResolvedPercentage = $vStats['minorResolvedPercentage'];
    $majorResolvedPercentage = $vStats['majorResolvedPercentage'];

    // Pagination
    $violationpage = Violation::paginate(10);

    // Get escalation notifications for admin
    $currentAdmin = Auth::user();
    $escalationNotifications = ViolationNotif::where('student_id', 'ROLE_ADMIN')
      ->where('ref_num', 'LIKE', 'ESCALATION-%')
      ->where('status', 0) // Unread
      ->orderBy('created_at', 'desc')
      ->limit(5)
      ->get();

    // Get trends analysis data for major offenses
    $trendsData = $this->trendsService->getMajorOffenseTrendsData(true);

    // Get minor offenses trends data
    $minorOffensesData = $this->trendsService->getMinorOffenseTrendsData(true);

    // Get authenticated admin data
    $admin = Auth::user();

    // Pass all to view
    return view('admin.dashboard', compact(
      'admin',
      'deptCounts',
      'minorpending',
      'minorcomplied',
      'majorpending',
      'majorcomplied',
      'pendingPercent',
      'compliedPercent',
      'dashArray',
      'majorPendingPercent',
      'majorCompliedPercent',
      'majorDashArray',
      'violationpage',
      'departments',
      'majorCounts',
      'minorCounts',
      'majorViolationsByDept',
      'minorViolationsByDept',
      'minorTotal',
      'majorTotal',
      'minorResolved',
      'minorPending',
      'majorResolved',
      'majorPending',
      'minorResolvedPercentage',
      'majorResolvedPercentage',
      'frequency',
      'escalationNotifications',
      'trendsData',
      'minorOffensesData'
    ) + [
      'frequencyOptions' => $this->getFrequencyOptions(),
      'frequencyLabel' => $this->getFrequencyLabel($frequency)
    ]);
  }
}
