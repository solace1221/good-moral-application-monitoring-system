<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentOfficerApplication;
use App\Models\RoleAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentOfficerController extends Controller
{
  public function index(Request $request)
  {
    $status = $request->get('status', 'pending');

    if (!in_array($status, ['pending', 'approved', 'rejected', 'revoked'])) {
      $status = 'pending';
    }

    $applications = StudentOfficerApplication::with(['student', 'organization', 'position', 'reviewer'])
      ->where('status', $status)
      ->orderBy('created_at', 'desc')
      ->get();

    return view('admin.student-officer-applications', compact('applications', 'status'));
  }

  public function approve($id)
  {
    $application = StudentOfficerApplication::with(['student', 'organization', 'position'])->findOrFail($id);

    // Allow re-syncing roles for already-approved applications (idempotent)
    if (!in_array($application->status, ['pending', 'approved'])) {
      return redirect()->route('admin.studentOfficerApplications')->with('error', 'This application has already been rejected.');
    }

    try {
      DB::transaction(function () use ($application) {
        // Update the application status
        $application->update([
          'status' => 'approved',
          'reviewed_by' => Auth::id(),
          'reviewed_at' => now(),
        ]);

        // Sync the officer role to both tables
        $this->assignOfficerRole($application);
      });

      return redirect()->route('admin.studentOfficerApplications')->with('status', 'Student Officer application approved. Student role has been updated.');
    } catch (\Exception $e) {
      return redirect()->route('admin.studentOfficerApplications')->with('error', 'Failed to approve application: ' . $e->getMessage());
    }
  }

  public function reject($id)
  {
    $application = StudentOfficerApplication::findOrFail($id);

    if ($application->status !== 'pending') {
      return redirect()->route('admin.studentOfficerApplications')->with('error', 'This application has already been processed.');
    }

    $application->update([
      'status' => 'rejected',
      'reviewed_by' => Auth::id(),
      'reviewed_at' => now(),
    ]);

    return redirect()->route('admin.studentOfficerApplications')->with('status', 'Student Officer application rejected.');
  }

  public function revoke($id)
  {
    $application = StudentOfficerApplication::with('student')->findOrFail($id);

    if ($application->status !== 'approved') {
      return redirect()->route('admin.studentOfficerApplications')->with('error', 'Only approved applications can be revoked.');
    }

    try {
      DB::transaction(function () use ($application) {
        // Revert the application status to revoked (distinct from rejected to avoid unique constraint)
        $application->update([
          'status' => 'revoked',
          'reviewed_by' => Auth::id(),
          'reviewed_at' => now(),
        ]);

        // Revert the officer role from both tables
        $this->revokeOfficerRole($application);
      });

      return redirect()->route('admin.studentOfficerApplications', ['status' => 'approved'])->with('status', 'Student Officer access has been revoked. Student role restored.');
    } catch (\Exception $e) {
      return redirect()->route('admin.studentOfficerApplications', ['status' => 'approved'])->with('error', 'Failed to revoke: ' . $e->getMessage());
    }
  }

  public function reconsider($id)
  {
    $application = StudentOfficerApplication::findOrFail($id);

    if (!in_array($application->status, ['rejected', 'revoked'])) {
      return redirect()->route('admin.studentOfficerApplications')->with('error', 'Only rejected or revoked applications can be reconsidered.');
    }

    $application->update([
      'status' => 'pending',
      'reviewed_by' => null,
      'reviewed_at' => null,
    ]);

    return redirect()->route('admin.studentOfficerApplications')->with('status', 'Student Officer application moved back to pending for reconsideration.');
  }

  /**
   * Assign officer role to both role_account and users tables.
   */
  private function assignOfficerRole(StudentOfficerApplication $application): void
  {
    $student = $application->student;

    $student->update([
      'account_type' => 'psg_officer',
      'organization' => $application->organization?->description,
      'position' => $application->position?->position_title,
    ]);

    User::where('email', $student->email)->update([
      'role' => 'psg_officer',
    ]);
  }

  /**
   * Revert officer role back to student in both tables.
   */
  private function revokeOfficerRole(StudentOfficerApplication $application): void
  {
    $student = $application->student;

    $student->update([
      'account_type' => 'student',
      'organization' => null,
      'position' => null,
    ]);

    User::where('email', $student->email)->update([
      'role' => 'student',
    ]);
  }
}
