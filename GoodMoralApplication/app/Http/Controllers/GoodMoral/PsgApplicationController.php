<?php

namespace App\Http\Controllers\GoodMoral;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Position;
use App\Models\PsgApplication;
use App\Models\RoleAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PsgApplicationController extends Controller
{
    /**
     * Show the PSG application form for students.
     */
    public function showForm()
    {
        $user = Auth::user();
        $student = RoleAccount::where('email', $user->email)->first();

        if (!$student || $student->account_type !== 'student') {
            return redirect()->route('dashboard')->with('error', 'Only active students can apply for PSG Officer role.');
        }

        // Check if student already has a pending application
        $pendingApplication = PsgApplication::where('student_id', $student->id)
            ->where('status', 'pending')
            ->first();

        // Check if student is already an approved PSG officer
        $approvedApplication = PsgApplication::where('student_id', $student->id)
            ->where('status', 'approved')
            ->first();

        $organizations = Organization::orderBy('description')->get();

        return view('student.apply-psg', compact('student', 'organizations', 'pendingApplication', 'approvedApplication'));
    }

    /**
     * Handle the PSG application submission.
     */
    public function apply(Request $request)
    {
        $user = Auth::user();
        $student = RoleAccount::where('email', $user->email)->first();

        if (!$student || $student->account_type !== 'student') {
            return redirect()->route('dashboard')->with('error', 'Only active students can apply for PSG Officer role.');
        }

        // Prevent duplicate pending applications
        $existing = PsgApplication::where('student_id', $student->id)
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return redirect()->route('student.applyPsg')->with('error', 'You already have a pending PSG application.');
        }

        // Prevent if already approved
        $approved = PsgApplication::where('student_id', $student->id)
            ->where('status', 'approved')
            ->exists();

        if ($approved) {
            return redirect()->route('student.applyPsg')->with('error', 'You are already an approved PSG Officer.');
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
            return redirect()->route('student.applyPsg')->with('error', 'Invalid position for the selected organization.');
        }

        PsgApplication::create([
            'student_id' => $student->id,
            'organization_id' => $request->organization_id,
            'position_id' => $request->position_id,
            'status' => 'pending',
        ]);

        return redirect()->route('student.applyPsg')->with('status', 'Your PSG Officer application has been submitted successfully!');
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
