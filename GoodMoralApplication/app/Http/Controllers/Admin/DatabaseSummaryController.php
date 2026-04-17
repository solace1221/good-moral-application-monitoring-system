<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ViolationNotif;
use App\Models\GoodMoralApplication;
use App\Models\StudentViolation;
use App\Models\RoleAccount;
use App\Models\StudentRegistration;
use App\Models\Violation;
use App\Models\ArchivedRoleAccount;
use App\Models\HeadOSAApplication;
use App\Models\DeanApplication;
use App\Models\NotifArchive;
use App\Models\SecOSAApplication;
use App\Models\AcademicYear;
use App\Models\GeneratedReport;
use App\Services\DashboardStatsService;

class DatabaseSummaryController extends Controller
{
  public function databaseSummary()
  {
    // Check authentication
    if (!Auth::check()) {
      return redirect()->route('login');
    }

    // 1. USERS & ACCOUNTS
    $totalUsers = RoleAccount::count();
    $usersByRole = RoleAccount::select('account_type', DB::raw('count(*) as count'))
      ->groupBy('account_type')
      ->pluck('count', 'account_type')
      ->toArray();
    
    $archivedUsersByRole = ArchivedRoleAccount::select('account_type', DB::raw('count(*) as count'))
      ->groupBy('account_type')
      ->pluck('count', 'account_type')
      ->toArray();

    // 2. STUDENT REGISTRATIONS
    $totalStudents = StudentRegistration::count();
    $departments = DashboardStatsService::getDepartments();

    // Aggregate student registrations by department and gender in a single query
    $studentAggregates = StudentRegistration::select(
        'department',
        DB::raw('COUNT(*) as total'),
        DB::raw("SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) as male"),
        DB::raw("SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) as female")
      )
      ->whereIn('department', $departments)
      ->groupBy('department')
      ->get()
      ->keyBy('department');

    $studentsByDepartment = [];
    $totalStudentCount = 0;

    foreach ($departments as $dept) {
      $row = $studentAggregates->get($dept);
      $total = $row ? (int) $row->total : 0;
      $studentsByDepartment[$dept] = [
        'total' => $total,
        'male' => $row ? (int) $row->male : 0,
        'female' => $row ? (int) $row->female : 0,
        'percentage' => 0 // Will calculate after
      ];
      $totalStudentCount += $total;
    }

    // Calculate percentages
    foreach ($studentsByDepartment as $dept => &$data) {
      $data['percentage'] = $totalStudentCount > 0 ? ($data['total'] / $totalStudentCount) * 100 : 0;
    }

    // 3. GOOD MORAL APPLICATIONS
    $totalApplications = GoodMoralApplication::count();
    $applicationsByStatus = GoodMoralApplication::select('status', DB::raw('count(*) as count'))
      ->groupBy('status')
      ->pluck('count', 'status')
      ->toArray();

    $applicationsByDepartment = [];

    // Aggregate applications by department and status in a single query
    $appAggregates = GoodMoralApplication::select(
        'department',
        DB::raw('COUNT(*) as total'),
        DB::raw("SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending"),
        DB::raw("SUM(CASE WHEN status = 'Approved by Administrator' THEN 1 ELSE 0 END) as approved"),
        DB::raw("SUM(CASE WHEN status IN ('Rejected by SEC-OSA', 'Rejected by HEAD-OSA', 'Rejected by DEAN', 'Rejected by Administrator') THEN 1 ELSE 0 END) as rejected"),
        DB::raw("SUM(CASE WHEN status = 'Ready for Pickup' THEN 1 ELSE 0 END) as ready")
      )
      ->whereIn('department', $departments)
      ->groupBy('department')
      ->get()
      ->keyBy('department');

    foreach ($departments as $dept) {
      $row = $appAggregates->get($dept);
      $applicationsByDepartment[$dept] = [
        'total' => $row ? (int) $row->total : 0,
        'pending' => $row ? (int) $row->pending : 0,
        'approved' => $row ? (int) $row->approved : 0,
        'rejected' => $row ? (int) $row->rejected : 0,
        'ready' => $row ? (int) $row->ready : 0,
      ];
    }

    // 4. VIOLATIONS
    $totalViolations = StudentViolation::count();
    $violationsByType = [
      'minor' => StudentViolation::where('offense_type', 'minor')->count(),
      'major' => StudentViolation::where('offense_type', 'major')->count(),
    ];

    $violationsByStatus = [
      'resolved' => StudentViolation::where('status', 2)->count(),
      'pending' => StudentViolation::where('status', '!=', 2)->count(),
    ];

    $violationsByDepartment = [];

    // Aggregate violations by department, type, and status in a single query
    $violAggregates = StudentViolation::select(
        'department',
        DB::raw('COUNT(*) as total'),
        DB::raw("SUM(CASE WHEN offense_type = 'minor' THEN 1 ELSE 0 END) as minor"),
        DB::raw("SUM(CASE WHEN offense_type = 'major' THEN 1 ELSE 0 END) as major"),
        DB::raw("SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as resolved"),
        DB::raw("SUM(CASE WHEN status != 2 THEN 1 ELSE 0 END) as pending")
      )
      ->whereIn('department', $departments)
      ->groupBy('department')
      ->get()
      ->keyBy('department');

    foreach ($departments as $dept) {
      $row = $violAggregates->get($dept);
      $violationsByDepartment[$dept] = [
        'total' => $row ? (int) $row->total : 0,
        'minor' => $row ? (int) $row->minor : 0,
        'major' => $row ? (int) $row->major : 0,
        'resolved' => $row ? (int) $row->resolved : 0,
        'pending' => $row ? (int) $row->pending : 0,
      ];
    }

    // 5. RECEIPTS & PAYMENTS
    $totalReceipts = DB::table('receipts')->count();
    $totalRevenue = DB::table('receipts')->sum('amount') ?? 0;
    $averagePayment = $totalReceipts > 0 ? $totalRevenue / $totalReceipts : 0;

    $receiptsByPaymentMethod = DB::table('receipts')
      ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
      ->groupBy('payment_method')
      ->get()
      ->mapWithKeys(function ($item) use ($totalRevenue) {
        return [$item->payment_method => [
          'count' => $item->count,
          'total' => $item->total ?? 0,
          'percentage' => $totalRevenue > 0 ? ($item->total / $totalRevenue) * 100 : 0
        ]];
      })
      ->toArray();

    // 6. ARCHIVED RECORDS
    $archivedAccounts = ArchivedRoleAccount::count();
    $archivedNotifications = NotifArchive::count();

    // 7. DATABASE TABLES OVERVIEW
    $databaseTables = [
      ['name' => 'users', 'count' => DB::table('users')->count(), 'description' => 'System user authentication'],
      ['name' => 'role_account', 'count' => RoleAccount::count(), 'description' => 'User roles and accounts'],
      ['name' => 'student_registrations', 'count' => StudentRegistration::count(), 'description' => 'Student registration records'],
      ['name' => 'good_moral_applications', 'count' => GoodMoralApplication::count(), 'description' => 'Good moral certificate applications'],
      ['name' => 'student_violations', 'count' => StudentViolation::count(), 'description' => 'Student violation records'],
      ['name' => 'violations', 'count' => Violation::count(), 'description' => 'Violation types and definitions'],
      ['name' => 'violation_notifs', 'count' => ViolationNotif::count(), 'description' => 'Violation notifications'],
      ['name' => 'receipts', 'count' => DB::table('receipts')->count(), 'description' => 'Payment receipts'],
      ['name' => 'courses', 'count' => DB::table('courses')->count(), 'description' => 'Course catalog'],
      ['name' => 'academic_years', 'count' => AcademicYear::count(), 'description' => 'Academic year records'],
      ['name' => 'head_osa_applications', 'count' => HeadOSAApplication::count(), 'description' => 'Head OSA approval records'],
      ['name' => 'dean_applications', 'count' => DeanApplication::count(), 'description' => 'Dean approval records'],
      ['name' => 'sec_osa_applications', 'count' => SecOSAApplication::count(), 'description' => 'SEC OSA approval records'],
      ['name' => 'archived_role_accounts', 'count' => ArchivedRoleAccount::count(), 'description' => 'Archived user accounts'],
      ['name' => 'notifarchives', 'count' => NotifArchive::count(), 'description' => 'Archived notifications'],
      ['name' => 'generated_reports', 'count' => GeneratedReport::count(), 'description' => 'Generated report history'],
    ];

    return view('admin.database-summary', compact(
      'totalUsers',
      'usersByRole',
      'archivedUsersByRole',
      'totalStudents',
      'studentsByDepartment',
      'totalApplications',
      'applicationsByStatus',
      'applicationsByDepartment',
      'totalViolations',
      'violationsByType',
      'violationsByStatus',
      'violationsByDepartment',
      'totalReceipts',
      'totalRevenue',
      'averagePayment',
      'receiptsByPaymentMethod',
      'archivedAccounts',
      'archivedNotifications',
      'databaseTables'
    ));
  }

  public function downloadDatabaseSummaryPDF()
  {
    // This will be implemented with PDF generation
    return response()->json(['message' => 'PDF download feature coming soon']);
  }

  public function downloadDatabaseSummaryExcel()
  {
    // This will be implemented with Excel generation
    return response()->json(['message' => 'Excel download feature coming soon']);
  }
}
