<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoleAccount;
use App\Models\ArchivedRoleAccount;
use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Http\Request;

class PsgController extends Controller
{
  public function psgApplication(Request $request)
  {
    $status = $request->get('status'); // Get the filter status from the URL

    // Apply filter based on status
    if ($status == 'approved') {
      $applications = RoleAccount::where('status', '1')->where('account_type', 'psg_officer')->get();
    } elseif ($status == 'rejected') {
      $applications = ArchivedRoleAccount::where('status', '3')->where('account_type', 'psg_officer')->get(); // Default to Pending
    } else {
      $applications = RoleAccount::where('status', '5')->where('account_type', 'psg_officer')->get();
    }

    return view('admin.psg-application', compact('applications'));
  }

  public function rejectpsg($student_id)
  {
    // Retrieve the application from the RoleAccount table
    $application = RoleAccount::where('student_id', $student_id)->firstOrFail();

    // Prepare the data to be transferred to the new table
    $archivedApplication = new ArchivedRoleAccount();
    $archivedApplication->student_id = $application->student_id;
    $archivedApplication->fullname = $application->fullname;
    $archivedApplication->department = $application->department;
    $archivedApplication->status = '3'; // Rejected status
    $archivedApplication->account_type = $application->account_type;
    $archivedApplication->created_at = $application->created_at; // Ensure you keep the created_at
    $archivedApplication->updated_at = $application->updated_at; // Same for updated_at
    // Add any other fields you need to transfer
    $archivedApplication->save();

    // Delete the original application from the RoleAccount table
    $application->delete();
    $id = $application->student_id;

    // Deactivate the login account
    User::where('email', $application->email)->update(['status' => 'inactive']);

    // Delete the original registraton from the registration table
    $registration = StudentRegistration::where('student_id', $id)->first();
    if ($registration) {
      $registration->delete();
    }


    // Redirect with a success message
    return redirect()->route('admin.psgApplication')->with('status', 'Application rejected and moved to archive.');
  }


  public function approvepsg($student_id)
  {
    $application = RoleAccount::where('student_id', $student_id)->firstOrFail();
    $application->status = '1';
    $application->save();

    // Activate the login account so the PSG officer can log in
    User::where('email', $application->email)->update(['status' => 'active']);

    $applicationStudent = StudentRegistration::where('student_id', $student_id)->first();
    if ($applicationStudent) {
      $applicationStudent->status = '1';
      $applicationStudent->save();
    }

    return redirect()->route('admin.psgApplication')->with('status', 'Application approved.');
  }

  public function revokePsg($student_id)
  {
    // Retrieve the application from the RoleAccount table
    $application = RoleAccount::where('student_id', $student_id)->firstOrFail();

    // Prepare the data to be transferred to the archived table
    $archivedApplication = new ArchivedRoleAccount();
    $archivedApplication->student_id = $application->student_id;
    $archivedApplication->fullname = $application->fullname;
    $archivedApplication->department = $application->department;
    $archivedApplication->status = '3'; // Revoked status (same as rejected)
    $archivedApplication->account_type = $application->account_type;
    $archivedApplication->created_at = $application->created_at;
    $archivedApplication->updated_at = now(); // Update timestamp for revocation
    $archivedApplication->save();

    // Delete the original application from the RoleAccount table
    $application->delete();

    // Update the student registration status to revoked
    $registration = StudentRegistration::where('student_id', $student_id)->firstOrFail();
    $registration->status = '3'; // Revoked status
    $registration->save();

    return redirect()->route('admin.psgApplication')->with('status', 'PSG Officer approval has been revoked successfully.');
  }

  public function reconsiderPsg($student_id)
  {
    // Check if this is a rejected application (in archived table)
    $archivedApplication = ArchivedRoleAccount::where('student_id', $student_id)->where('account_type', 'psg_officer')->first();

    if ($archivedApplication) {
      // This is a rejected application - move it back to active table
      $roleAccount = new RoleAccount();
      $roleAccount->student_id = $archivedApplication->student_id;
      $roleAccount->fullname = $archivedApplication->fullname;
      $roleAccount->department = $archivedApplication->department;
      $roleAccount->account_type = $archivedApplication->account_type;
      $roleAccount->status = '5'; // Pending status for reconsideration
      $roleAccount->created_at = $archivedApplication->created_at;
      $roleAccount->updated_at = now();

      // Get additional fields from student registration if they exist
      $studentRegistration = StudentRegistration::where('student_id', $student_id)->first();
      if ($studentRegistration) {
        $roleAccount->email = $studentRegistration->email;
        $roleAccount->password = $studentRegistration->password;
        // Update student registration status
        $studentRegistration->status = '5';
        $studentRegistration->save();
      }

      $roleAccount->save();

      // Remove from archived table
      $archivedApplication->delete();

      return redirect()->route('admin.psgApplication')->with('status', 'Rejected PSG Officer application moved to pending for reconsideration.');
    } else {
      // This might be an approved application - change status back to pending
      $application = RoleAccount::where('student_id', $student_id)->firstOrFail();

      // Change status back to pending for reconsideration
      $application->status = '5'; // Pending status
      $application->save();

      // Update the student registration status to pending
      $applicationStudent = StudentRegistration::where('student_id', $student_id)->firstOrFail();
      $applicationStudent->status = '5'; // Pending status
      $applicationStudent->save();

      return redirect()->route('admin.psgApplication')->with('status', 'PSG Officer application moved to pending for reconsideration.');
    }
  }
}
