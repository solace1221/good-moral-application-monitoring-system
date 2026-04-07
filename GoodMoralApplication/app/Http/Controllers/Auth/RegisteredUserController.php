<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentRegistration;
use App\Models\RoleAccount;
use App\Helpers\CourseHelper;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Violation;

class RegisteredUserController extends Controller
{

  /**
   * Format fullname in "First Middle Last Extension" format
   */
  private function formatFullname($fname, $mname, $lname, $extension)
  {
    $name = $fname;

    if ($mname) {
      // Add middle initial with period
      $name .= ' ' . substr($mname, 0, 1) . '.';
    }

    $name .= ' ' . $lname;

    if ($extension) {
      $name .= ' ' . $extension;
    }

    return $name;
  }

  /**
   * Display the registration view.
   */
  public function create()
  {
    try {
      $coursesByDepartment = CourseHelper::getCoursesByDepartment();
      $departments = CourseHelper::getAllDepartments();

      // If no courses found in database, provide fallback data
      if (empty($coursesByDepartment)) {
        $coursesByDepartment = [
          'SITE' => ['BSIT', 'BLIS', 'BS ENSE', 'BS CpE', 'BSCE'],
          'SBAHM' => ['BSA', 'BSE', 'BSBAMM', 'BSBA MFM', 'BSBA MOP', 'BSMA', 'BSHM', 'BSTM', 'BSPDMI'],
          'SASTE' => ['BAELS', 'BS Psych', 'BS Bio', 'BSSW', 'BSPA', 'BS Bio MB', 'BSEd', 'BEEd', 'BPEd'],
          'SNAHS' => ['BSN', 'BSPh', 'BSMT', 'BSPT', 'BSRT'],
          'SOM' => ['Doctor of Medicine'],
          'GRADSCH' => ['Master of Arts', 'Master of Science', 'Doctor of Philosophy'],
        ];
        $departments = array_keys($coursesByDepartment);
      }
    } catch (\Exception $e) {
      // Fallback data in case of any errors
      $coursesByDepartment = [
        'SITE' => ['BSIT', 'BLIS', 'BS ENSE', 'BS CpE', 'BSCE'],
        'SBAHM' => ['BSA', 'BSE', 'BSBAMM', 'BSBA MFM', 'BSBA MOP', 'BSMA', 'BSHM', 'BSTM', 'BSPDMI'],
        'SASTE' => ['BAELS', 'BS Psych', 'BS Bio', 'BSSW', 'BSPA', 'BS Bio MB', 'BSEd', 'BEEd', 'BPEd'],
        'SNAHS' => ['BSN', 'BSPh', 'BSMT', 'BSPT', 'BSRT'],
        'SOM' => ['Doctor of Medicine'],
        'GRADSCH' => ['Master of Arts', 'Master of Science', 'Doctor of Philosophy'],
      ];
      $departments = array_keys($coursesByDepartment);
    }

    return view('auth.register', compact('coursesByDepartment', 'departments'));
  }

  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): RedirectResponse
  {
    // Base validation rules
    $rules = [
      'fname' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
      'mname' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z\s]*$/'],
      'lname' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
      'extension' => ['nullable', 'string', 'max:10', 'regex:/^[A-Za-z\s]*$/'],
      'gender' => ['required', 'string', 'in:male,female'],
      'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:student_registrations,email'],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
      'department' => ['required', 'string',  'in:SITE,SBAHM,SASTE,SNAHS,SOM,GRADSCH'],
      'student_id' => ['required', 'string', 'max:20', 'unique:student_registrations'],
      'account_type' => ['required', 'string', 'in:student,alumni,psg_officer'],
      'year_level' => ['nullable', 'string', 'max:50'],
      'organization' => ['nullable', 'string', 'max:255'],
      'position' => ['nullable', 'string', 'max:255'],
    ];

    // Conditional validation based on account type
    if ($request->account_type === 'psg_officer') {
      $rules['organization'] = ['required', 'string', 'max:255'];
      $rules['position'] = ['required', 'string', 'max:255'];
    } elseif ($request->account_type === 'student') {
      $rules['year_level'] = ['required', 'string', 'max:50'];
    }

    $request->validate($rules);

    // Format the fullname
    $fullname = $this->formatFullname($request->fname, $request->mname, $request->lname, $request->extension);

    // Extract course from year_level (e.g., "BSIT - 1st Year" -> "BSIT")
    $course = null;
    if ($request->year_level && strpos($request->year_level, ' - ') !== false) {
      $parts = explode(' - ', $request->year_level);
      $course = trim($parts[0]);
    }

    if ($request->account_type !== 'psg_officer') {
      // Create user in local users table for authentication
      $localUser = User::create([
        'name' => strtolower($request->fname . '.' . $request->lname),
        'firstname' => $request->fname,
        'lastname' => $request->lname,
        'middlename' => $request->mname,
        'suffix_name' => $request->extension,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->account_type === 'alumni' ? 'student' : 'student',
        'status' => 'active',
      ]);

      $user = StudentRegistration::create([
        'fname' => $request->fname,
        'mname' => $request->mname,
        'lname' => $request->lname,
        'extension' => $request->extension,
        'gender' => $request->gender,
        'email' => $request->email,
        'department' => $request->department,
        'password' => Hash::make($request->password), // Always hash passwords
        'student_id' => $request->student_id,
        'status' => "1",
        'account_type' => $request->account_type,
        'year_level' => $request->year_level,
        'organization' => $request->organization,
        'position' => $request->position,
      ]);

      $user1 = RoleAccount::create([
        'fullname' => $fullname,
        'mname' => $request->mname,
        'extension' => $request->extension,
        'gender' => $request->gender,
        'department' => $request->department,
        'email' => $request->email,
        'password' => Hash::make($request->password), // Always hash passwords
        'student_id' => $request->student_id,
        'status' => "1",
        'account_type' => $request->account_type,
        'year_level' => $request->year_level,
        'organization' => $request->organization,
        'position' => $request->position,
      ]);

      return redirect(route('login'))->with('status', 'Your account was succesfully created.');
    } else {
      // Create user in local users table for PSG officers
      $localUser = User::create([
        'name' => strtolower($request->fname . '.' . $request->lname),
        'firstname' => $request->fname,
        'lastname' => $request->lname,
        'middlename' => $request->mname,
        'suffix_name' => $request->extension,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'officer',
        'status' => 'inactive', // Pending approval
        'employee_id' => $request->student_id,
      ]);

      $user = StudentRegistration::create([
        'fname' => $request->fname,
        'mname' => $request->mname,
        'lname' => $request->lname,
        'extension' => $request->extension,
        'gender' => $request->gender,
        'email' => $request->email,
        'department' => $request->department,
        'password' => Hash::make($request->password), // Always hash passwords
        'student_id' => $request->student_id,
        'status' => "5",
        'account_type' => $request->account_type,
        'year_level' => $request->year_level,
        'organization' => $request->organization,
        'position' => $request->position,
      ]);

      $user1 = RoleAccount::create([
        'fullname' => $fullname,
        'mname' => $request->mname,
        'extension' => $request->extension,
        'gender' => $request->gender,
        'department' => $request->department,
        'email' => $request->email,
        'password' => Hash::make($request->password), // Always hash passwords
        'student_id' => $request->student_id,
        'status' => "5",
        'account_type' => $request->account_type,
        'year_level' => $request->year_level,
        'organization' => $request->organization,
        'position' => $request->position,
      ]);

      return redirect(route('login'))->with('status', 'Your account is pending approval. Please wait for further instructions.');
    }
  }
}
