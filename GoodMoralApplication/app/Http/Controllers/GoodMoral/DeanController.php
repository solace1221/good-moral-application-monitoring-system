<?php

namespace App\Http\Controllers\GoodMoral;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\DeanApplication;
use App\Models\SecOSAApplication;
use App\Models\GoodMoralApplication;
use App\Models\NotifArchive;
use Illuminate\Support\Facades\Auth;
use App\Traits\RoleCheck;
use App\Traits\DateFilterTrait;
use App\Models\StudentViolation;
use App\Models\Violation;
use App\Models\HeadOSAApplication;
use Illuminate\Support\Str;
use App\Models\ViolationNotif;
use App\Services\GoodMoralWorkflowService;
use App\Services\NotificationArchiveService;

class DeanController extends Controller
{
  /**
   * Show the dashboard with pending applications.
   *
   * @return \Illuminate\View\View
   */
  use RoleCheck, DateFilterTrait;

  protected GoodMoralWorkflowService $workflowService;
  protected NotificationArchiveService $notifService;

  public function __construct(
    GoodMoralWorkflowService $workflowService,
    NotificationArchiveService $notifService
  ) {
    $this->workflowService = $workflowService;
    $this->notifService = $notifService;
  }

  public function dashboard(Request $request)
  {
    // User authentication already verified by login system
    $dean = Auth::user();
    $department = $dean->department;

    // Map department abbreviations to possible full names for matching violations
    $departmentMap = [
      'SITE' => ['SITE', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING'],
      'SNAHS' => ['SNAHS', 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES'],
      'SBAHM' => ['SBAHM', 'SCHOOL OF BUSINESS ADMINISTRATION AND HOSPITALITY MANAGEMENT'],
      'SASTE' => ['SASTE', 'SCHOOL OF ARTS, SCIENCES, TEACHER EDUCATION'],
      'SOM' => ['SOM', 'SCHOOL OF MEDICINE'],
      'GRADSCH' => ['GRADSCH', 'GRADUATE SCHOOL'],
    ];
    
    // Get possible department names for filtering violations
    $possibleDepartments = $departmentMap[$department] ?? [$department];

    // Get frequency filter from request
    $frequency = $request->get('frequency', 'all');

    //Applicants per course with date filtering - FILTERED BY DEAN'S DEPARTMENT
    // Updated to match both full course names and abbreviations stored in database
    $bsit = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSIT', 'Bachelor of Science in Information Technology']), $frequency)->count();

    $blis = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BLIS', 'Bachelor of Library and Information Science']), $frequency)->count();

    $bsce = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSCE', 'Bachelor of Science in Civil Engineering']), $frequency)->count();

    $bscpe = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSCpE', 'Bachelor of Science in Computer Engineering']), $frequency)->count();

    $bsense = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSENSE', 'Bachelor of Science in Environmental Science and Engineering']), $frequency)->count();

    $bsn = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSN', 'Bachelor of Science in Nursing']), $frequency)->count();

    $bsph = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSPh', 'Bachelor of Science in Pharmacy']), $frequency)->count();

    $bsmt = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSMT', 'Bachelor of Science in Medical Technology']), $frequency)->count();

    $bspt = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSPT', 'Bachelor of Science in Physical Therapy']), $frequency)->count();

    $bsrt = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSRT', 'Bachelor of Science in Radiologic Technology']), $frequency)->count();

    $bsm = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSM', 'Bachelor of Science in Midwifery']), $frequency)->count();

    $bsa = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSA', 'Bachelor of Science in Accountancy']), $frequency)->count();

    $bse = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSE', 'Bachelor of Science in Entrepreneurship']), $frequency)->count();

    $bsbamm = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSBAMM', 'Bachelor of Science in Business Administration Major in Marketing Management']), $frequency)->count();

    $bsbamfm = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSBAMFM', 'Bachelor of Science in Business Administration Major in Financial Management']), $frequency)->count();

    $bsbamop = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSBAMOP', 'Bachelor of Science in Business Administration Major in Operations Management']), $frequency)->count();

    $bsma = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSMA', 'Bachelor of Science in Management Accounting']), $frequency)->count();

    $bshm = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSHM', 'Bachelor of Science in Hospitality Management']), $frequency)->count();

    $bstm = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSTM', 'Bachelor of Science in Tourism Management']), $frequency)->count();

    $bspdmi = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSPDMI', 'Bachelor of Science in Product Development and Management Innovation']), $frequency)->count();

    $baels = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BAELS', 'Bachelor of Arts in English Language Studies']), $frequency)->count();

    $bspsych = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSPsych', 'Bachelor of Science in Psychology']), $frequency)->count();

    $bsbio = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSBio', 'Bachelor of Science in Biology']), $frequency)->count();

    $bssw = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSSW', 'Bachelor of Science in Social Work']), $frequency)->count();

    $bsbpa = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSBPA', 'Bachelor of Science in Public Administration']), $frequency)->count();

    $bsbiomb = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSBioMB', 'Bachelor of Science in Biology Major in Microbiology']), $frequency)->count();

    $bsed = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BSEd', 'Bachelor of Science in Education']), $frequency)->count();

    $beed = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BEEd', 'Bachelor of Elementary Education']), $frequency)->count();

    $bped = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BPEd', 'Bachelor of Physical Education']), $frequency)->count();

    // SOM programs
    $md = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['MD', 'Doctor of Medicine']), $frequency)->count();

    $bsmed = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['BS Med', 'Bachelor of Science in Medicine']), $frequency)->count();

    // Graduate School programs
    $mba = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['MBA', 'Master of Business Administration']), $frequency)->count();

    $mpa = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['MPA', 'Master of Public Administration']), $frequency)->count();

    $med = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['MEd', 'Master of Education']), $frequency)->count();

    $ms = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['MS', 'Master of Science']), $frequency)->count();

    $ma = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['MA', 'Master of Arts']), $frequency)->count();

    $phd = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['PhD', 'Doctor of Philosophy']), $frequency)->count();

    $edd = $this->applyDateFilter(GoodMoralApplication::where('department', $department)
      ->whereIn('course_completed', ['EdD', 'Doctor of Education']), $frequency)->count();

    $SITEprograms = [
      ['abbr1' => 'BS', 'abbr2' => 'IT', 'count' => $bsit],
      ['abbr1' => 'BL', 'abbr2' => 'IS', 'count' => $blis],
      ['abbr1' => 'BS', 'abbr2' => 'CE', 'count' => $bsce],
      ['abbr1' => 'BS', 'abbr2' => 'CpE', 'count' => $bscpe],
      ['abbr1' => 'BS', 'abbr2' => 'ENSE', 'count' => $bsense],
    ];

    $SNAHSprograms = [
      ['abbr1' => 'BS', 'abbr2' => 'N', 'count' => $bsn],
      ['abbr1' => 'BS', 'abbr2' => 'Ph', 'count' => $bsph],
      ['abbr1' => 'BS', 'abbr2' => 'MT', 'count' => $bsmt],
      ['abbr1' => 'BS', 'abbr2' => 'PT', 'count' => $bspt],
      ['abbr1' => 'BS', 'abbr2' => 'RT', 'count' => $bsrt],
      ['abbr1' => 'BS', 'abbr2' => 'M', 'count' => $bsm],
    ];

    $SBAHMprograms = [
      ['abbr1' => 'BS', 'abbr2' => 'A', 'count' => $bsa],
      ['abbr1' => 'BS', 'abbr2' => 'E', 'count' => $bse],
      ['abbr1' => 'BSBA', 'abbr2' => 'MM', 'count' => $bsbamm],
      ['abbr1' => 'BSBA', 'abbr2' => 'MFM', 'count' => $bsbamfm],
      ['abbr1' => 'BSBA', 'abbr2' => 'MOP', 'count' => $bsbamop],
      ['abbr1' => 'BS', 'abbr2' => 'MA', 'count' => $bsma],
      ['abbr1' => 'BS', 'abbr2' => 'HM', 'count' => $bshm],
      ['abbr1' => 'BS', 'abbr2' => 'TM', 'count' => $bstm],
      ['abbr1' => 'BS', 'abbr2' => 'PDMI', 'count' => $bspdmi],
    ];
    $SBAHMfirstRow = array_slice($SBAHMprograms, 0, 4);
    $SBAHMsecondRow = array_slice($SBAHMprograms, 4, 5);

    $SASTEprograms = [
      ['abbr1' => 'BA', 'abbr2' => 'ELS', 'count' => $baels],
      ['abbr1' => 'BS', 'abbr2' => 'Psych', 'count' => $bspsych],
      ['abbr1' => 'BS', 'abbr2' => 'Bio', 'count' => $bsbio],
      ['abbr1' => 'BS', 'abbr2' => 'SW', 'count' => $bssw],
      ['abbr1' => 'BS', 'abbr2' => 'PA', 'count' => $bsbpa],
      ['abbr1' => 'BS', 'abbr2' => 'Bio MB', 'count' => $bsbiomb],
      ['abbr1' => 'BS', 'abbr2' => 'Ed', 'count' => $bsed],
      ['abbr1' => 'BE', 'abbr2' => 'Ed', 'count' => $beed],
      ['abbr1' => 'B', 'abbr2' => 'PEd', 'count' => $bped],
    ];
    $SASTEfirstRow = array_slice($SASTEprograms, 0, 4);
    $SASTEsecondRow = array_slice($SASTEprograms, 4, 5);

    $SOMprograms = [
      ['abbr1' => 'M', 'abbr2' => 'D', 'count' => $md],
      ['abbr1' => 'BS', 'abbr2' => 'Med', 'count' => $bsmed],
    ];

    $GRADSCHprograms = [
      ['abbr1' => 'M', 'abbr2' => 'BA', 'count' => $mba],
      ['abbr1' => 'M', 'abbr2' => 'PA', 'count' => $mpa],
      ['abbr1' => 'M', 'abbr2' => 'Ed', 'count' => $med],
      ['abbr1' => 'M', 'abbr2' => 'S', 'count' => $ms],
      ['abbr1' => 'M', 'abbr2' => 'A', 'count' => $ma],
      ['abbr1' => 'Ph', 'abbr2' => 'D', 'count' => $phd],
      ['abbr1' => 'Ed', 'abbr2' => 'D', 'count' => $edd],
    ];
    $GRADSCHfirstRow = array_slice($GRADSCHprograms, 0, 4);
    $GRADSCHsecondRow = array_slice($GRADSCHprograms, 4, 3);

    $programs = [];
    $programsRow1 = [];
    $programsRow2 = [];

    if ($department === 'SITE') {
      $programs = $SITEprograms;
    } elseif ($department === 'SNAHS') {
      $programs = $SNAHSprograms;
    } elseif ($department === 'SBAHM') {
      $programsRow1 = $SBAHMfirstRow;
      $programsRow2 = $SBAHMsecondRow;
    } elseif ($department === 'SASTE') {
      $programsRow1 = $SASTEfirstRow;
      $programsRow2 = $SASTEsecondRow;
    } elseif ($department === 'SOM') {
      $programs = $SOMprograms;
    } elseif ($department === 'GRADSCH') {
      $programsRow1 = $GRADSCHfirstRow;
      $programsRow2 = $GRADSCHsecondRow;
    }

    //For Pie Chart stats with date filtering
    $minorpending = $this->applyDateFilter(StudentViolation::whereIn('department', $possibleDepartments)->where('status', '!=', 2)->where('offense_type', 'minor'), $frequency)->count();
    $minorcomplied = $this->applyDateFilter(StudentViolation::whereIn('department', $possibleDepartments)->where('status', '=', 2)->where('offense_type', 'minor'), $frequency)->count();
    $majorpending = $this->applyDateFilter(StudentViolation::whereIn('department', $possibleDepartments)->where('status', '!=', 2)->where('offense_type', 'major'), $frequency)->count();
    $majorcomplied = $this->applyDateFilter(StudentViolation::whereIn('department', $possibleDepartments)->where('status', '=', 2)->where('offense_type', 'major'), $frequency)->count();

    // Minor violations by program for the dean's department with date filtering
    $minorViolationsQuery = StudentViolation::query()->where('offense_type', 'minor')
      ->whereIn('department', $possibleDepartments);
    $minorViolationsData = $this->applyDateFilter($minorViolationsQuery, $frequency)
      ->selectRaw('course as program, COUNT(*) as count')
      ->groupBy('course')
      ->orderBy('count', 'desc')
      ->get();

    // Major violations by program for the dean's department with date filtering
    $majorViolationsQuery = StudentViolation::query()->where('offense_type', 'major')
      ->whereIn('department', $possibleDepartments);
    $majorViolationsData = $this->applyDateFilter($majorViolationsQuery, $frequency)
      ->selectRaw('course as program, COUNT(*) as count')
      ->groupBy('course')
      ->orderBy('count', 'desc')
      ->get();

    // Get all programs for this department
    $allProgramsList = [];
    if ($department === 'SITE') {
      $allProgramsList = ['BSIT', 'BLIS', 'BSCE', 'BSCpE', 'BSENSE'];
    } elseif ($department === 'SNAHS') {
      $allProgramsList = ['BSN', 'BSPh', 'BSMT', 'BSPT', 'BSRT', 'BSM'];
    } elseif ($department === 'SBAHM') {
      $allProgramsList = ['BSA', 'BSE', 'BSBAMM', 'BSBAMFM', 'BSBAMOP', 'BSMA', 'BSHM', 'BSTM', 'BSPDMI'];
    } elseif ($department === 'SASTE') {
      $allProgramsList = ['BAELS', 'BSPsych', 'BSBio', 'BSSW', 'BSBPA', 'BSBioMB', 'BSEd', 'BEEd', 'BPEd'];
    } elseif ($department === 'SOM') {
      $allProgramsList = ['MD', 'BS Med'];
    } elseif ($department === 'GRADSCH') {
      $allProgramsList = ['MBA', 'MPA', 'MEd', 'MS', 'MA', 'PhD', 'EdD'];
    }

    // Create comprehensive lists including programs with 0 violations
    $minorViolationsByProgram = collect($allProgramsList)->map(function ($program) use ($minorViolationsData) {
      $existing = $minorViolationsData->firstWhere('program', $program);
      return (object) [
        'program' => $program,
        'count' => $existing ? $existing->count : 0
      ];
    })->sortByDesc('count');

    $majorViolationsByProgram = collect($allProgramsList)->map(function ($program) use ($majorViolationsData) {
      $existing = $majorViolationsData->firstWhere('program', $program);
      return (object) [
        'program' => $program,
        'count' => $existing ? $existing->count : 0
      ];
    })->sortByDesc('count');

    // Combined violations by program for comprehensive view with date filtering
    $allViolationsByProgram = $this->applyDateFilter(StudentViolation::query()->whereIn('department', $possibleDepartments), $frequency)
      ->selectRaw('course as program, offense_type, COUNT(*) as count')
      ->groupBy('course', 'offense_type')
      ->orderBy('course')
      ->get()
      ->groupBy('program');

    // Calculate percentages for charts
    $minorTotal = $minorpending + $minorcomplied;
    $majorTotal = $majorpending + $majorcomplied;
    $minorResolvedPercentage = $minorTotal > 0 ? ($minorcomplied / $minorTotal) * 100 : 0;
    $majorResolvedPercentage = $majorTotal > 0 ? ($majorcomplied / $majorTotal) * 100 : 0;

    // Get recent students with violations for the dean's department (last 10)
    $recentViolations = StudentViolation::whereIn('department', $possibleDepartments)
      ->orderBy('created_at', 'desc')
      ->take(10)
      ->get();

    // Count pending Good Moral applications awaiting dean approval with date filtering
    $pendingGoodMoralApplications = $this->applyDateFilter(GoodMoralApplication::where(function($query) {
        $query->where('application_status', 'LIKE', 'Approved By Registrar%')
              ->orWhere('application_status', 'LIKE', 'Approved by Registrar%')
              ->orWhere('application_status', '=', 'Approved By Registrar')
              ->orWhere('application_status', '=', 'Approved by Registrar');
      })
      ->where('department', $department)
      ->where('certificate_type', 'good_moral')
      ->whereNotNull('application_status'), $frequency)->count();

    // Count pending Residency applications awaiting dean approval with date filtering
    $pendingResidencyApplications = $this->applyDateFilter(GoodMoralApplication::where(function($query) {
        $query->where('application_status', 'LIKE', 'Approved By Registrar%')
              ->orWhere('application_status', 'LIKE', 'Approved by Registrar%')
              ->orWhere('application_status', '=', 'Approved By Registrar')
              ->orWhere('application_status', '=', 'Approved by Registrar');
      })
      ->where('department', $department)
      ->where('certificate_type', 'residency')
      ->whereNotNull('application_status'), $frequency)->count();

    //Pageinate
    $violationpage = Violation::paginate(10);
    return view('dean.dashboard', compact(
      'minorpending',
      'minorcomplied',
      'majorpending',
      'majorcomplied',
      'violationpage',
      'SITEprograms',
      'SNAHSprograms',
      'SBAHMprograms',
      'SASTEprograms',
      'SOMprograms',
      'GRADSCHprograms',
      'SBAHMfirstRow',
      'SBAHMsecondRow',
      'SASTEfirstRow',
      'SASTEsecondRow',
      'GRADSCHfirstRow',
      'GRADSCHsecondRow',
      'department',
      'programs',
      'programsRow1',
      'programsRow2',
      'minorViolationsByProgram',
      'majorViolationsByProgram',
      'allViolationsByProgram',
      'minorResolvedPercentage',
      'majorResolvedPercentage',
      'minorTotal',
      'majorTotal',
      'frequency',
      'pendingGoodMoralApplications',
      'pendingResidencyApplications',
      'recentViolations'
    ) + [
      'frequencyOptions' => $this->getFrequencyOptions(),
      'frequencyLabel' => $this->getFrequencyLabel($frequency)
    ]);
  }

  /**
   * Get notification counts for dean sidebar
   */
  public function getNotificationCounts()
  {
    $dean = Auth::user();
    $department = $dean->department;

    // Map department abbreviations to possible full names
    $departmentMap = [
      'SITE' => ['SITE', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING'],
      'SNAHS' => ['SNAHS', 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES'],
      'SBAHM' => ['SBAHM', 'SCHOOL OF BUSINESS ADMINISTRATION AND HOSPITALITY MANAGEMENT'],
      'SASTE' => ['SASTE', 'SCHOOL OF ARTS, SCIENCES, TEACHER EDUCATION'],
      'SOM' => ['SOM', 'SCHOOL OF MEDICINE'],
      'GRADSCH' => ['GRADSCH', 'GRADUATE SCHOOL'],
    ];
    
    // Get possible department names for filtering
    $possibleDepartments = $departmentMap[$department] ?? [$department];

    // Count pending Good Moral applications that need dean approval
    $pendingApplications = GoodMoralApplication::where(function($query) {
        $query->where('application_status', 'LIKE', 'Approved By Registrar%')
              ->orWhere('application_status', 'LIKE', 'Approved by Registrar%')
              ->orWhere('application_status', '=', 'Approved By Registrar')
              ->orWhere('application_status', '=', 'Approved by Registrar');
      })
      ->where('department', $department)
      ->whereNotNull('application_status')
      ->count();

    // Count pending major violations in dean's department
    $majorViolations = StudentViolation::where('offense_type', 'major')
      ->whereIn('department', $possibleDepartments)
      ->where('status', 0) // Pending status
      ->count();

    // Count pending minor violations in dean's department
    $minorViolations = StudentViolation::where('offense_type', 'minor')
      ->whereIn('department', $possibleDepartments)
      ->where('status', 0) // Pending status
      ->count();

    return response()->json([
      'pendingApplications' => $pendingApplications,
      'majorViolations' => $majorViolations,
      'minorViolations' => $minorViolations,
    ]);
  }

  public function application()
  {
    try {
      // Access the authenticated dean
      $dean = Auth::user();

      // Validate dean authentication
      if (!$dean) {
        \Log::error('Dean Application Access: No authenticated user');
        return redirect()->route('login')->with('error', 'Please login to access applications.');
      }

      if (!in_array($dean->account_type, ['dean'])) {
        \Log::error('Dean Application Access: User is not a dean', ['user_type' => $dean->account_type]);
        return redirect()->route('dashboard')->with('error', 'Access denied. Dean privileges required.');
      }



      // Fetch pending applications assigned to the dean's department from DeanApplication (legacy)
      $legacyApplications = DeanApplication::where('status', 'pending')
        ->where('department', $dean->department) // Filtering by department
        ->with('student') // Eager load the related student data
        ->get();

      // Fetch Good Moral Applications that need dean approval
      $goodMoralApplications = GoodMoralApplication::where(function($query) {
          $query->where('application_status', 'LIKE', 'Approved By Registrar%')
                ->orWhere('application_status', 'LIKE', 'Approved by Registrar%')
                ->orWhere('application_status', '=', 'Approved By Registrar')
                ->orWhere('application_status', '=', 'Approved by Registrar');
        })
        ->where('department', $dean->department)
        ->where('certificate_type', 'good_moral')
        ->whereNotNull('application_status')
        ->orderBy('updated_at', 'desc')
        ->get();

      // Fetch Residency Applications that need dean approval
      $residencyApplications = GoodMoralApplication::where(function($query) {
          $query->where('application_status', 'LIKE', 'Approved By Registrar%')
                ->orWhere('application_status', 'LIKE', 'Approved by Registrar%')
                ->orWhere('application_status', '=', 'Approved By Registrar')
                ->orWhere('application_status', '=', 'Approved by Registrar');
        })
        ->where('department', $dean->department)
        ->where('certificate_type', 'residency')
        ->whereNotNull('application_status')
        ->orderBy('updated_at', 'desc')
        ->get();

      // Combine all applications for total count
      $allApplications = $goodMoralApplications->merge($residencyApplications);



      // Organize applications by type
      $applications = [
        'legacy' => $legacyApplications,
        'good_moral' => $goodMoralApplications,
        'residency' => $residencyApplications,
        'all_new' => $allApplications
      ];

      return view('dean.application', [
        'applications' => $applications,
        'department' => $dean->department, // pass department to view
      ]);

    } catch (\Exception $e) {
      \Log::error('Dean Application Error', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'user_id' => auth()->id()
      ]);

      return redirect()->route('dean.dashboard')->with('error', 'Unable to load applications. Please try again.');
    }
  }

  /**
   * Approve a Dean application.
   *
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function approve($id)
  {
    try {
      // Retrieve the application
      $application = DeanApplication::findOrFail($id);
      $dean = Auth::user();

      // Check if the logged-in dean has permission to approve the application
      if ($application->department !== $dean->department) {
        return redirect()->route('dean.dashboard')->with('error', 'Unauthorized access to application.');
      }

      // Prevent approving already approved applications
      if ($application->status == 'approved') {
        return redirect()->route('dean.dashboard')->with('error', 'This application has already been approved.');
      }

      // Update the application status to 'approved'
      $application->status = 'approved';
      $application->save();

      // Create SecOSA application if it doesn't already exist
      $student = $application->student;
      if (!$student) {
        return redirect()->route('dean.dashboard')->with('error', 'Student not found.');
      }

      // Prevent creating duplicate SecOSAApplication
      if (SecOSAApplication::where('student_id', $student->student_id)->exists()) {
        return redirect()->route('dean.dashboard')->with('error', 'SecOSA application already exists for this student.');
      }
      
      $student_id = $application->student_id;

      // Retrieve the GoodMoralApplication for the same student
      $goodMoralApplication = GoodMoralApplication::where('student_id', $student_id)->first();
      if ($goodMoralApplication) {
        // Update the application status for GoodMoralApplication
        $goodMoralApplication->application_status = 'Approved by Dean:' . $dean->fullname;
        $goodMoralApplication->save();
      }

      HeadOSAApplication::create([
        'number_of_copies' => $application->number_of_copies,
        'reference_number' => $application->reference_number,
        'student_id' => $student->student_id,
        'department' => $student->department,
        'reason' => $application->formatted_reasons, // Convert array to string
        'fullname' => $application->fullname,
        'course_completed' => $application->course_completed, // New field
        'graduation_date' => $application->graduation_date,   // New field
        'is_undergraduate' => $application->is_undergraduate, // New field
        'last_course_year_level' => $application->last_course_year_level, // New field
        'last_semester_sy' => $application->last_semester_sy,  // New field
        'status' => 'pending',
      ]);
      // Note: Notification will be created by the new system (approveGoodMoral method)
      // when the GoodMoralApplication is processed

      return back()->with('status', 'Application approved and forwarded to Office of Student Affairs.');
    } catch (\Exception $e) {
      return redirect()->route('dean.dashboard')->with('error', 'Error processing approval: ' . $e->getMessage());
    }
  }

  /**
   * Reject a Dean application.
   *
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function reject($id)
  {
    // Retrieve the application
    $application = DeanApplication::findOrFail($id);

    // Check if the logged-in dean has permission to reject the application
    $this->authorizeApplication($application);

    // Prevent rejecting already rejected applications
    if ($application->status == 'rejected') {
      return redirect()->route('dean.dashboard')->with('error', 'This application has already been rejected.');
    }
    $dean = Auth::user();
    $student_id = $application->student_id;

    // Retrieve the GoodMoralApplication for the same student
    $goodMoralApplication = GoodMoralApplication::where('student_id', $student_id)->first();
    if ($goodMoralApplication) {
      // Update the application status for GoodMoralApplication
      $goodMoralApplication->application_status = 'Rejected by Dean:' . $dean->fullname;
      $goodMoralApplication->save();
    }

    // Update the application status to 'rejected'
    $application->status = 'rejected';
    $application->save();

    if ($goodMoralApplication) {
      $this->notifService->createFromApplication($goodMoralApplication, '-3');
    }

    return redirect()->route('dean.dashboard')->with('status', 'Application rejected!');
  }

  /**
   * Approve a Good Moral Application.
   */
  public function approveGoodMoral($id)
  {
    // Get authenticated dean user
    $dean = Auth::user();
    if (!$dean) {
      return redirect()->route('login')->with('error', 'Authentication error');
    }
    
    try {
      $application = GoodMoralApplication::findOrFail($id);
      
      // Check if application belongs to dean's department
      if ($application->department !== $dean->department) {
        return redirect()->route('dean.application')->with('error', 'Unauthorized access to application.');
      }

      // Check if application is in correct status
      if (!str_contains($application->application_status, 'Approved By Registrar') && 
          !str_contains($application->application_status, 'Approved by Registrar')) {
        return redirect()->route('dean.application')->with('error', 'Application is not ready for dean approval.');
      }

      // Update application status and create notification via service
      $this->workflowService->approveByDean($application, $dean->fullname);

      $successMessage = "Good Moral application approved successfully! Application forwarded to Admin for final approval.";
      
      // Check if the request is AJAX/XHR
      if (request()->ajax() || request()->wantsJson()) {
        return response()->json([
          'success' => true, 
          'message' => $successMessage
        ]);
      }
      
      // Regular response for non-AJAX requests
      return redirect()->route('dean.application')->with('status', $successMessage);
    } catch (\Exception $e) {
      if (request()->ajax() || request()->wantsJson()) {
        return response()->json([
          'success' => false,
          'error' => $e->getMessage()
        ], 500);
      }
      
      return redirect()->route('dean.application')->with('error', 'Error approving application: ' . $e->getMessage());
    }
  }

  /**
   * Reject a Good Moral Application.
   */
  public function rejectGoodMoral($id)
  {
    $dean = Auth::user();
    $application = GoodMoralApplication::findOrFail($id);

    // Check if application belongs to dean's department
    if ($application->department !== $dean->department) {
      return redirect()->route('dean.application')->with('error', 'Unauthorized access to application.');
    }

    // Check if application is in correct status
    if (!str_contains($application->application_status, 'Approved By Registrar')) {
      return redirect()->route('dean.application')->with('error', 'Application is not ready for dean action.');
    }

    // Update application status and create notification via service
    $this->workflowService->rejectByDean($application, $dean->fullname);

    return redirect()->route('dean.application')->with('status', 'Good Moral application rejected successfully!');
  }

  /**
   * Reject application with detailed reason.
   */
  public function rejectWithReason(Request $request, $id)
  {
    $request->validate([
      'rejection_reason' => 'required|string|max:255',
      'rejection_details' => 'nullable|string|max:1000',
    ]);

    $dean = Auth::user();
    $application = GoodMoralApplication::findOrFail($id);

    // Check if application belongs to dean's department
    if ($application->department !== $dean->department) {
      return redirect()->route('dean.application')->with('error', 'Unauthorized access to application.');
    }

    // Update application with rejection details and create notification via service
    $this->workflowService->rejectByDean($application, $dean->fullname, $request->rejection_reason, $request->rejection_details);

    return redirect()->route('dean.application')->with('status', 'Application rejected successfully.');
  }

  /**
   * Reconsider a rejected application.
   */
  public function reconsider(Request $request, $id)
  {
    $request->validate([
      'reconsider_notes' => 'nullable|string|max:1000',
    ]);

    $dean = Auth::user();
    $application = GoodMoralApplication::findOrFail($id);

    // Check if application belongs to dean's department
    if ($application->department !== $dean->department) {
      return redirect()->route('dean.application')->with('error', 'Unauthorized access to application.');
    }

    // Reset application status
    $application->status = 'pending';
    $application->application_status = 'Approved By Registrar ' . ($application->application_status ? explode('Approved By Registrar', $application->application_status)[1] ?? '' : '');
    $application->action_history = ($application->action_history ?? '') . "\n" . now()->format('Y-m-d H:i:s') . " - Reconsidered by Dean: {$dean->fullname}" . ($request->reconsider_notes ? " (Notes: {$request->reconsider_notes})" : "");
    $application->save();

    // Update notification
    $notification = NotifArchive::where('reference_number', $application->reference_number)->first();
    if ($notification) {
      $notification->status = '1'; // Back to dean approval
      $notification->application_status = 'Reconsidered by Dean';
      $notification->save();
    }

    return redirect()->route('dean.application')->with('status', 'Application reconsidered successfully.');
  }

  /**
   * Get application details for AJAX requests.
   */
  public function getApplicationDetails($id)
  {
    $application = GoodMoralApplication::findOrFail($id);

    return response()->json([
      'rejection_reason' => $application->rejection_reason,
      'rejection_details' => $application->rejection_details,
      'rejected_by' => $application->rejected_by,
      'rejected_at' => $application->rejected_at,
      'action_history' => $application->action_history,
    ]);
  }

  /**
   * Authorize that the logged-in dean can approve/reject the application.
   *
   * @param  \App\Models\DeanApplication  $application
   * @return void
   */
  protected function authorizeApplication($application)
  {
    $dean = Auth::user();

    // Check if the application belongs to the logged-in dean's department
    if ($application->department !== $dean->department) {
      abort(403, 'Unauthorized access to application.');
    }
  }
  public function major()
  {
    $userDepartment = Auth::user()->department;

    // Map department abbreviations to possible full names
    $departmentMap = [
      'SITE' => ['SITE', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING'],
      'SNAHS' => ['SNAHS', 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES'],
      'SBAHM' => ['SBAHM', 'SCHOOL OF BUSINESS ADMINISTRATION AND HOSPITALITY MANAGEMENT'],
      'SASTE' => ['SASTE', 'SCHOOL OF ARTS, SCIENCES, TEACHER EDUCATION'],
      'SOM' => ['SOM', 'SCHOOL OF MEDICINE'],
      'GRADSCH' => ['GRADSCH', 'GRADUATE SCHOOL'],
    ];

    // Get possible department names for filtering
    $possibleDepartments = $departmentMap[$userDepartment] ?? [$userDepartment];

    $students = StudentViolation::whereIn('department', $possibleDepartments)
      ->where('offense_type', 'major')
      ->orderBy('created_at', 'desc') // Most recent violations first
      ->paginate(10);

    return view('dean.major', compact('students'));
  }


  public function minor()
  {
    $userDepartment = Auth::user()->department;

    // Map department abbreviations to possible full names
    $departmentMap = [
      'SITE' => ['SITE', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING'],
      'SNAHS' => ['SNAHS', 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES'],
      'SBAHM' => ['SBAHM', 'SCHOOL OF BUSINESS ADMINISTRATION AND HOSPITALITY MANAGEMENT'],
      'SASTE' => ['SASTE', 'SCHOOL OF ARTS, SCIENCES, TEACHER EDUCATION'],
      'SOM' => ['SOM', 'SCHOOL OF MEDICINE'],
      'GRADSCH' => ['GRADSCH', 'GRADUATE SCHOOL'],
    ];

    // Get possible department names for filtering
    $possibleDepartments = $departmentMap[$userDepartment] ?? [$userDepartment];

    $students = StudentViolation::whereIn('department', $possibleDepartments)
      ->where('offense_type', 'minor')
      ->orderBy('created_at', 'desc') // Most recent violations first
      ->paginate(10);

    return view('dean.minor', compact('students'));
  }


  public function deanviolationapprove($id)
  {
    $userDepartment = Auth::user()->department;

    $violation = StudentViolation::findOrFail($id);

    // For minor violations, approve and send to Admin for final approval
    if ($violation->offense_type === 'minor') {
      $violation->status = "1"; // Mark as Dean approved, pending Admin approval
      $violation->save();

      ViolationNotif::create([
        'ref_num' => 'DEAN-APPROVED',
        'student_id' => $violation->student_id,
        'status' => 0,  // pending status
        'notif' => "Your minor violation has been approved by the Dean ({$userDepartment}). The case is now pending Admin final approval. Please wait for further instructions.",
      ]);

      return back()->with('success', "Minor violation approved by Dean! Sent to Admin for final approval.");
    } else {
      // For major violations, generate case number (existing logic)
      $date = date('Ymd');
      do {
        $unique = strtoupper(Str::random(6));  // 6 random uppercase letters/numbers
        $caseNumber = "CASE-{$date}-{$unique}";
        $exists = StudentViolation::where('ref_num', $caseNumber)->exists();
      } while ($exists);
      $violation->ref_num = $caseNumber;
      $violation->status = "1";

      $violation->save();
      // Get the violation details to find article reference
      $violationRecord = \App\Models\Violation::where('description', $violation->violation)->first();
      $article = $violationRecord ? $violationRecord->article : null;

      // Create notification using new format
      $proceedingsMessage = generateHandbookReference($violation->offense_type, $article) . ". Your violation proceedings have been approved by the Dean with case number: {$caseNumber}. Please proceed to the Administrator for final resolution.";

      ViolationNotif::create([
        'ref_num' => $caseNumber,
        'student_id' => $violation->student_id,
        'status' => 0,  // initial status
        'notif' => $proceedingsMessage,
      ]);

      return back()->with('success', "Approve the proceedings with Case number: {$caseNumber}");
    }
  }
}
