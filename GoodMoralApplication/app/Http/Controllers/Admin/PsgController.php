<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PsgApplication;
use App\Models\RoleAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PsgController extends Controller
{
  public function psgApplication(Request $request)
  {
    $status = $request->get('status', 'pending');

    $applications = PsgApplication::with(['student', 'organization', 'position', 'reviewer'])
      ->where('status', $status)
      ->orderBy('created_at', 'desc')
      ->get();

    return view('admin.psg-application', compact('applications', 'status'));
  }

  public function approvepsg($id)
  {
    $application = PsgApplication::with(['student', 'organization', 'position'])->findOrFail($id);

    // Allow re-syncing roles for already-approved applications (idempotent)
    if (!in_array($application->status, ['pending', 'approved'])) {
      return redirect()->route('admin.psgApplication')->with('error', 'This application has already been rejected.');
    }

    try {
      DB::transaction(function () use ($application) {
        // Update the PSG application status
        $application->update([
          'status' => 'approved',
          'reviewed_by' => Auth::id(),
          'reviewed_at' => now(),
        ]);

        // Sync the PSG officer role to both tables
        $this->assignPsgRole($application);
      });

      return redirect()->route('admin.psgApplication')->with('status', 'PSG Officer application approved. Student role has been updated.');
    } catch (\Exception $e) {
      return redirect()->route('admin.psgApplication')->with('error', 'Failed to approve application: ' . $e->getMessage());
    }
  }

  public function rejectpsg($id)
  {
    $application = PsgApplication::findOrFail($id);

    if ($application->status !== 'pending') {
      return redirect()->route('admin.psgApplication')->with('error', 'This application has already been processed.');
    }

    $application->update([
      'status' => 'rejected',
      'reviewed_by' => Auth::id(),
      'reviewed_at' => now(),
    ]);

    return redirect()->route('admin.psgApplication')->with('status', 'PSG Officer application rejected.');
  }

  public function revokePsg($id)
  {
    $application = PsgApplication::with('student')->findOrFail($id);

    if ($application->status !== 'approved') {
      return redirect()->route('admin.psgApplication')->with('error', 'Only approved applications can be revoked.');
    }

    try {
      DB::transaction(function () use ($application) {
        // Revert the application status to rejected
        $application->update([
          'status' => 'rejected',
          'reviewed_by' => Auth::id(),
          'reviewed_at' => now(),
        ]);

        // Revert the PSG officer role from both tables
        $this->revokePsgRole($application);
      });

      return redirect()->route('admin.psgApplication', ['status' => 'rejected'])->with('status', 'PSG Officer access has been revoked. Student role restored.');
    } catch (\Exception $e) {
      return redirect()->route('admin.psgApplication', ['status' => 'approved'])->with('error', 'Failed to revoke: ' . $e->getMessage());
    }
  }

  public function reconsiderPsg($id)
  {
    $application = PsgApplication::findOrFail($id);

    if ($application->status !== 'rejected') {
      return redirect()->route('admin.psgApplication')->with('error', 'Only rejected applications can be reconsidered.');
    }

    $application->update([
      'status' => 'pending',
      'reviewed_by' => null,
      'reviewed_at' => null,
    ]);

    return redirect()->route('admin.psgApplication')->with('status', 'PSG Officer application moved back to pending for reconsideration.');
  }

  /**
   * Assign PSG officer role to both role_account and users tables.
   */
  private function assignPsgRole(PsgApplication $application): void
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
   * Revert PSG officer role back to student in both tables.
   */
  private function revokePsgRole(PsgApplication $application): void
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
