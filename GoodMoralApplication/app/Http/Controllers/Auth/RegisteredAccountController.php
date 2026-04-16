<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentRegistration;
use App\Models\RoleAccount;
use App\Models\Organization;
use App\Models\Course;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Http\Requests\StoreAccountRequest;


class RegisteredAccountController extends Controller
{
  public function create(Request $request): View
  {
    // Students query (account_type = 'student' or 'alumni')
    $studentsQuery = RoleAccount::whereIn('account_type', ['student', 'alumni']);
    if ($request->filled('search_name') && $request->get('subtab', 'students') !== 'admin') {
      $studentsQuery->where('fullname', 'LIKE', '%' . $request->search_name . '%');
    }
    if ($request->filled('search_student_id')) {
      $studentsQuery->where('student_id', 'LIKE', '%' . $request->search_student_id . '%');
    }
    $students = $studentsQuery->orderBy('fullname', 'asc')->paginate(10)->appends($request->query());

    // Administrative accounts query (excludes students)
    $adminQuery = RoleAccount::whereNotIn('account_type', ['student', 'alumni']);
    if ($request->filled('search_name') && $request->get('subtab') === 'admin') {
      $adminQuery->where('fullname', 'LIKE', '%' . $request->search_name . '%');
    }
    if ($request->filled('search_department')) {
      $adminQuery->where('department', $request->search_department);
    }
    $adminAccounts = $adminQuery->orderBy('account_type')->orderBy('fullname')->paginate(10)->appends($request->query());

    $organizations = Organization::orderBy('description')->get();

    $courses = Course::ordered()->get();

    return view('admin.add-account', compact('students', 'adminAccounts', 'organizations', 'courses'));
  }

  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(StoreAccountRequest $request): RedirectResponse
  {
    // Parse fullname into parts for sync
    $nameParts = $this->parseFullname($request->fullname);

    // Hash password once to use everywhere
    $hashedPassword = Hash::make($request->password);

    // Map account_type to users.role
    $roleMap = [
      'student'     => 'student',
      'alumni'      => 'alumni',
      'admin'       => 'admin',
      'dean'        => 'dean',
      'registrar'   => 'registrar',
      'sec_osa'     => 'sec_osa',
      'prog_coor'   => 'prog_coor',
    ];

    $userRole = $roleMap[$request->account_type] ?? $request->account_type;

    try {
    DB::transaction(function () use ($request, $nameParts, $hashedPassword, $userRole) {
      // Resolve course_id to course_code for backward-compat text column
      $courseCode = null;
      $courseId = $request->course_id;
      if ($courseId) {
        $course = Course::find($courseId);
        $courseCode = $course?->course_code;
      }

      // 1. Always create login record in users table
      User::create([
        'name' => strtolower(trim($nameParts['firstname'] . '.' . $nameParts['lastname'], '.')),
        'firstname' => $nameParts['firstname'],
        'lastname' => $nameParts['lastname'],
        'middlename' => $nameParts['middlename'],
        'suffix_name' => $nameParts['extension'],
        'email' => $request->email,
        'password' => $hashedPassword,
        'role' => $userRole,
        'status' => 'active',
      ]);

      // 2. Always create profile in role_account
      // Only student accounts get academic fields (course_id, course, year_level)
      $isStudent = $request->account_type === 'student';
      $staffRoles = ['admin', 'dean', 'registrar', 'sec_osa', 'prog_coor', 'psg_officer'];
      $isStaff = in_array($request->account_type, $staffRoles);

      RoleAccount::create([
        'fullname' => $request->fullname,
        'email' => $request->email,
        'department' => $request->department,
        'password' => $hashedPassword,
        'student_id' => $isStaff ? null : $request->student_id,
        'course_id' => $isStudent ? $courseId : null,
        'course' => $isStudent ? $courseCode : null,
        'year_level' => $isStudent ? $request->year_level : null,
        'organization' => $request->organization,
        'status' => 'active',
        'account_type' => $request->account_type,
      ]);

      // 3. Create student_registrations only for student roles
      if (in_array($request->account_type, ['student', 'alumni'])) {
        StudentRegistration::create([
          'fname' => $nameParts['firstname'],
          'mname' => $nameParts['middlename'],
          'lname' => $nameParts['lastname'],
          'extension' => $nameParts['extension'],
          'email' => $request->email,
          'department' => $request->department,
          'course_id' => $courseId,
          'course' => $courseCode,
          'password' => $hashedPassword,
          'student_id' => $request->student_id,
          'status' => 'active',
          'account_type' => $request->account_type,
          'year_level' => $request->year_level,
        ]);
      }
    });

    return redirect()->route('admin.AddAccount')->with('success', 'Account successfully created!');
    } catch (\Exception $e) {
      Log::error('Account creation failed', [
        'email' => $request->email,
        'account_type' => $request->account_type,
        'error' => $e->getMessage(),
      ]);

      return redirect()->route('admin.AddAccount')->with('error', 'Failed to create account. Please try again.');
    }
  }

  /**
   * Parse fullname into component parts
   * Expected format: "LASTNAME, FIRSTNAME MIDDLENAME EXTENSION"
   */
  private function parseFullname($fullname)
  {
    $parts = [
      'firstname' => '',
      'lastname' => '',
      'middlename' => '',
      'extension' => ''
    ];

    // Split by comma to separate lastname from firstname/middle/extension
    if (strpos($fullname, ',') !== false) {
      list($lastname, $rest) = array_map('trim', explode(',', $fullname, 2));
      $parts['lastname'] = $lastname;

      // Split the rest by spaces
      $restParts = array_filter(explode(' ', $rest));
      $restParts = array_values($restParts);

      if (count($restParts) >= 1) {
        $parts['firstname'] = $restParts[0];
      }
      if (count($restParts) >= 2) {
        // Check if last part is extension (Jr., Sr., III, etc.)
        $lastPart = end($restParts);
        if (preg_match('/^(Jr\.?|Sr\.?|I{1,3}|IV|V)$/i', $lastPart)) {
          $parts['extension'] = $lastPart;
          // Middle name is everything between firstname and extension
          if (count($restParts) > 2) {
            $parts['middlename'] = implode(' ', array_slice($restParts, 1, -1));
          }
        } else {
          // No extension, middle name is everything after firstname
          $parts['middlename'] = implode(' ', array_slice($restParts, 1));
        }
      }
    } else {
      // No comma, assume format: "FIRSTNAME MIDDLENAME LASTNAME"
      $allParts = array_filter(explode(' ', $fullname));
      $allParts = array_values($allParts);

      if (count($allParts) >= 1) {
        $parts['firstname'] = $allParts[0];
      }
      if (count($allParts) >= 2) {
        $parts['lastname'] = end($allParts);
      }
      if (count($allParts) >= 3) {
        $parts['middlename'] = implode(' ', array_slice($allParts, 1, -1));
      }
    }

    return $parts;
  }
}
