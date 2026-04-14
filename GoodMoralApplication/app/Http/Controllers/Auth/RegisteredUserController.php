<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentRegistration;
use App\Models\RoleAccount;
use App\Models\Course;
use App\Models\Organization;
use App\Models\Position;
use App\Helpers\CourseHelper;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Violation;
use App\Http\Requests\RegisterUserRequest;

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
    $departments = CourseHelper::getAllDepartments();

    // Load courses grouped by department code (with id, course_code, course_name)
    $coursesByDepartment = Course::ordered()
      ->get(['id', 'course_code', 'course_name', 'department'])
      ->groupBy('department')
      ->map(fn ($courses) => $courses->map(fn ($c) => [
        'id' => $c->id,
        'code' => $c->course_code,
        'name' => $c->course_name,
      ])->values());

    // Data for PSG officer fields
    $organizations = Organization::with('department')->orderBy('description')->get();
    $positions = Position::orderBy('position_title')->get();

    return view('auth.register', compact('coursesByDepartment', 'departments', 'organizations', 'positions'));
  }

  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(RegisterUserRequest $request): RedirectResponse
  {
    // Format the fullname
    $fullname = $this->formatFullname($request->fname, $request->mname, $request->lname, $request->extension);

    // Resolve course_id → course_code for backward-compatible text column
    $course = null;
    $courseId = $request->course_id;
    if ($courseId) {
      $courseRecord = Course::find($courseId);
      $course = $courseRecord?->course_code;
    }

    $hashedPassword = Hash::make($request->password);

    if ($request->account_type !== 'psg_officer') {
      DB::transaction(function () use ($request, $fullname, $course, $courseId, $hashedPassword) {
        // Create user in local users table for authentication
        User::create([
          'name' => strtolower($request->fname . '.' . $request->lname),
          'firstname' => $request->fname,
          'lastname' => $request->lname,
          'middlename' => $request->mname,
          'suffix_name' => $request->extension,
          'email' => $request->email,
          'password' => $hashedPassword,
          'role' => 'student',
          'status' => 'active',
        ]);

        StudentRegistration::create([
          'fname' => $request->fname,
          'mname' => $request->mname,
          'lname' => $request->lname,
          'extension' => $request->extension,
          'gender' => $request->gender,
          'email' => $request->email,
          'department' => $request->department,
          'course_id' => $courseId,
          'course' => $course,
          'password' => $hashedPassword,
          'student_id' => $request->student_id,
          'status' => '1',
          'account_type' => $request->account_type,
          'year_level' => $request->year_level,
          'organization' => $request->organization,
          'position' => $request->position,
        ]);

        RoleAccount::create([
          'fullname' => $fullname,
          'mname' => $request->mname,
          'extension' => $request->extension,
          'gender' => $request->gender,
          'department' => $request->department,
          'course_id' => $courseId,
          'course' => $course,
          'email' => $request->email,
          'password' => $hashedPassword,
          'student_id' => $request->student_id,
          'status' => '1',
          'account_type' => $request->account_type,
          'year_level' => $request->year_level,
          'organization' => $request->organization,
          'position' => $request->position,
        ]);
      });

      return redirect(route('login'))->with('status', 'Your account was succesfully created.');
    } else {
      DB::transaction(function () use ($request, $fullname, $course, $courseId, $hashedPassword) {
        // Create user in local users table for PSG officers
        User::create([
          'name' => strtolower($request->fname . '.' . $request->lname),
          'firstname' => $request->fname,
          'lastname' => $request->lname,
          'middlename' => $request->mname,
          'suffix_name' => $request->extension,
          'email' => $request->email,
          'password' => $hashedPassword,
          'role' => 'officer',
          'status' => 'inactive', // Pending approval
        ]);

        StudentRegistration::create([
          'fname' => $request->fname,
          'mname' => $request->mname,
          'lname' => $request->lname,
          'extension' => $request->extension,
          'gender' => $request->gender,
          'email' => $request->email,
          'department' => $request->department,
          'course_id' => $courseId,
          'course' => $course,
          'password' => $hashedPassword,
          'student_id' => $request->student_id,
          'status' => '5',
          'account_type' => $request->account_type,
          'year_level' => $request->year_level,
          'organization' => $request->organization,
          'position' => $request->position,
        ]);

        RoleAccount::create([
          'fullname' => $fullname,
          'mname' => $request->mname,
          'extension' => $request->extension,
          'gender' => $request->gender,
          'department' => $request->department,
          'course_id' => $courseId,
          'course' => $course,
          'email' => $request->email,
          'password' => $hashedPassword,
          'student_id' => $request->student_id,
          'status' => '5',
          'account_type' => $request->account_type,
          'year_level' => $request->year_level,
          'organization' => $request->organization,
          'position' => $request->position,
        ]);
      });

      return redirect(route('login'))->with('status', 'Your account is pending approval. Please wait for further instructions.');
    }
  }
}
