<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ViolationService;
use App\Models\ViolationNotif;
use App\Models\StudentViolation;
use Illuminate\Support\Facades\Auth;

class EscalationController extends Controller
{
  protected ViolationService $violationService;

  public function __construct(
    ViolationService $violationService
  ) {
    $this->violationService = $violationService;
  }

  /**
   * Show escalation notifications for students with 3 minor violations
   */
  public function escalationNotifications()
  {
    $escalationNotifications = $this->violationService->getEscalationNotificationsList();
    return view('admin.escalation-notifications', compact('escalationNotifications'));
  }

  /**
   * Manually trigger escalation for a student with 3+ minor violations
   */
  public function triggerManualEscalation($student_id)
  {
    try {
      // Get student information from their violations
      $studentInfo = StudentViolation::where('student_id', $student_id)
        ->where('offense_type', 'minor')
        ->first();

      if (!$studentInfo) {
        return response()->json([
          'success' => false,
          'message' => 'Student not found or has no minor violations.'
        ]);
      }

      // Count minor violations
      $minorViolationCount = StudentViolation::where('student_id', $student_id)
        ->where('offense_type', 'minor')
        ->count();

      if ($minorViolationCount < 3) {
        return response()->json([
          'success' => false,
          'message' => 'Student does not have 3 or more minor violations.'
        ]);
      }

      // Check if major violation already exists
      $existingMajorViolation = StudentViolation::where('student_id', $student_id)
        ->where('offense_type', 'major')
        ->where('violation', 'LIKE', '%Automatic escalation%')
        ->first();

      if ($existingMajorViolation) {
        return response()->json([
          'success' => false,
          'message' => 'Major violation already exists for this student.'
        ]);
      }

      // Create automatic major violation
      $admin = Auth::user();
      $referenceNumber = 'MAJ-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

      $majorViolation = StudentViolation::create([
        'student_id' => $student_id,
        'first_name' => $studentInfo->first_name,
        'last_name' => $studentInfo->last_name,
        'department' => $studentInfo->department,
        'course' => $studentInfo->course,
        'violation' => 'Automatic escalation: Accumulated 3 minor violations (Manual trigger by Admin)',
        'offense_type' => 'major',
        'ref_num' => $referenceNumber,
        'status' => 0, // Pending
        'added_by' => 'Admin: ' . $admin->fullname,
        'unique_id' => uniqid(),
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Major violation created successfully.',
        'violation_id' => $majorViolation->id,
        'reference_number' => $referenceNumber
      ]);

    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage()
      ]);
    }
  }

  /**
   * Mark escalation notification as read
   */
  public function markNotificationAsRead($id)
  {
    try {
      $notification = ViolationNotif::findOrFail($id);

      // Verify this is an escalation notification and belongs to current admin
      if (strpos($notification->ref_num, 'ESCALATION-') === 0 &&
          $notification->student_id === Auth::user()->student_id) {
        $notification->status = 1; // Mark as read
        $notification->save();

        return response()->json(['success' => true]);
      }

      return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Error occurred'], 500);
    }
  }
}
