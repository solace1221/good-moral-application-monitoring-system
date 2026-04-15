<?php

namespace App\Http\Controllers\GoodMoral;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Position;
use App\Models\StudentOfficerApplication;
use App\Models\RoleAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentOfficerApplicationController extends Controller
{
    /**
     * Show the student officer application form for students.
     */
    public function showForm()
    {
        $user = Auth::user();
        $student = RoleAccount::where('email', $user->email)->first();

        if (!$student || $student->account_type !== 'student') {
            return redirect()->route('dashboard')->with('error', 'Only active students can apply for a Student Officer role.');
        }

        // Check if student already has a pending application
        $pendingApplication = StudentOfficerApplication::where('student_id', $student->id)
            ->where('status', 'pending')
            ->first();

        // Check if student is already an approved officer
        $approvedApplication = StudentOfficerApplication::where('student_id', $student->id)
            ->where('status', 'approved')
            ->first();

        $organizations = Organization::orderBy('description')->get();

        return view('student.apply-officer', compact('student', 'organizations', 'pendingApplication', 'approvedApplication'));
    }

    /**
     * Handle the student officer application submission.
     */
    public function apply(Request $request)
    {
        $user = Auth::user();
        $student = RoleAccount::where('email', $user->email)->first();

        if (!$student || $student->account_type !== 'student') {
            return redirect()->route('dashboard')->with('error', 'Only active students can apply for a Student Officer role.');
        }

        // Prevent duplicate pending applications
        $existing = StudentOfficerApplication::where('student_id', $student->id)
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return redirect()->route('student.applyOfficer')->with('error', 'You already have a pending application.');
        }

        // Prevent if already approved
        $approved = StudentOfficerApplication::where('student_id', $student->id)
            ->where('status', 'approved')
            ->exists();

        if ($approved) {
            return redirect()->route('student.applyOfficer')->with('error', 'You are already an approved Student Officer.');
        }

        $request->validate([
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'position_id' => ['required', 'integer', 'exists:positions,id'],
        ]);

        // Verify the position belongs to the selected organization
        $position = Position::where('id', $request->position_id)
            ->where('organization_id', $request->organization_id)
            ->first();

        if (!$position) {
            return redirect()->route('student.applyOfficer')->with('error', 'Invalid position for the selected organization.');
        }

        StudentOfficerApplication::create([
            'student_id' => $student->id,
            'organization_id' => $request->organization_id,
            'position_id' => $request->position_id,
            'status' => 'pending',
        ]);

        return redirect()->route('student.applyOfficer')->with('status', 'Your Student Officer application has been submitted successfully!');
    }

    /**
     * Get positions for a given organization (AJAX endpoint).
     */
    public function getPositions($organizationId)
    {
        $positions = Position::where('organization_id', $organizationId)
            ->orderBy('position_title')
            ->get(['id', 'position_title']);

        return response()->json($positions);
    }
}
