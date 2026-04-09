<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodMoralApplication;
use App\Models\RoleAccount;
use App\Models\StudentViolation;

class NotificationController extends Controller
{
  /**
   * Get notification counts for admin sidebar
   */
  public function getNotificationCounts()
  {
    // Count pending Good Moral applications that need admin approval (approved by dean)
    $pendingApplications = GoodMoralApplication::where('application_status', 'LIKE', 'Approved by Dean:%')
      ->whereNotIn('application_status', ['Approved by Administrator', 'Rejected by Administrator'])
      ->count();

    // Count pending PSG applications
    $psgApplications = RoleAccount::where('account_type', 'psg_officer')
      ->where('status', '5') // Pending status
      ->count();

    // Count pending violations that need admin attention
    $pendingViolations = StudentViolation::where('status', 0) // Pending status
      ->count();

    // Count students with 3+ minor violations (escalation notifications)
    $escalationNotifications = StudentViolation::select('student_id')
      ->where('offense_type', 'minor')
      ->groupBy('student_id')
      ->havingRaw('COUNT(*) >= 3')
      ->count();

    return response()->json([
      'pendingApplications' => $pendingApplications,
      'psgApplications' => $psgApplications,
      'pendingViolations' => $pendingViolations,
      'escalationNotifications' => $escalationNotifications,
    ]);
  }
}
