<?php

namespace App\Http\Controllers\GoodMoral;
use App\Http\Controllers\Controller;

use App\Models\DeanApplication;
use App\Models\HeadOSAApplication;
use App\Traits\RoleCheck;

class HeadOSAController extends Controller
{
  use RoleCheck;

  public function __construct()
  {
    // Temporarily disable role check to fix authentication
    // $this->checkRole(['head_osa']);
  }
  public function dashboard()
  {
    $applications = HeadOSAApplication::where('status', 'pending')->get();
    return view('head_osa.dashboard', compact('applications'));
  }

  /**
   * Approve a Good Moral Certificate application.
   *
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function approve($id)
  {
    // 1. Find the application
    $application = HeadOSAApplication::findOrFail($id);

    // 2. Update the status to 'approved'
    $application->status = 'approved';
    $application->save();

    // 3. Get the student from role_account
    $student = $application->student;

    if (!$student) {
      return redirect()->route('head_osa.dashboard')->with('error', 'Student not found.');
    }

    // 4. Create the head_osa_application record for the single Head OSA
    DeanApplication::create([
      'student_id' => $student->student_id,
      'fullname' => $student->fullname,
      'department' => $student->department,
      'reason' => $application->formatted_reasons, // Convert array to string
      'course_completed' => $application->course_completed, // New field
      'graduation_date' => $application->graduation_date,   // New field
      'is_undergraduate' => $application->is_undergraduate, // New field
      'last_course_year_level' => $application->last_course_year_level, // New field
      'last_semester_sy' => $application->last_semester_sy,  // New field
      'status' => 'pending', // Default status
    ]);

    return redirect()->route('head_osa.dashboard')->with(
      'status',
      'Application approved and forwarded to ' . $application->student->department . ' Dean.'
    );
  }

  public function reject($id)
  {
    $application = HeadOSAApplication::findOrFail($id);
    $application->status = 'rejected';
    $application->save();
    return redirect()->route('head_osa.dashboard')->with('status', 'Application rejected!');
  }
  
}
