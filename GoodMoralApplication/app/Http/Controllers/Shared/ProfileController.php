<?php

namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Traits\RoleCheck;
use App\Models\RoleAccount;
use App\Models\StudentRegistration;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Determine the appropriate view based on user role
        $viewName = $this->getProfileViewName($user->account_type);

        return view($viewName, [
            'user' => $user,
        ]);
    }

    /**
     * Get the appropriate profile view name based on user role
     */
    private function getProfileViewName($accountType)
    {
        $roleViews = [
            'admin' => 'profile.admin',
            'moderator' => 'profile.moderator',
            'dean' => 'profile.dean',
            'prog_coor' => 'profile.prog_coor',
            'registrar' => 'profile.registrar',
            'student' => 'profile.student',
            'alumni' => 'profile.alumni',
            'psg_officer' => 'profile.psg_officer',
            'head_osa' => 'profile.head_osa',
            'sec_osa' => 'profile.sec_osa',
        ];

        return $roleViews[$accountType] ?? 'profile.edit';
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Add gender validation for all users
        $request->validate([
            'gender' => ['required', 'string', 'in:male,female'],
        ]);

        $user = $request->user();
        $user->fill($request->validated());
        $user->gender = $request->gender;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update graduation status for the authenticated user.
     */
    public function updateGraduationStatus(Request $request)
    {
        $user = $request->user();

        // Only students can update their graduation status
        if ($user->account_type !== 'student') {
            return response()->json([
                'success' => false,
                'message' => 'Only students can update graduation status.'
            ], 403);
        }

        $request->validate([
            'is_graduating' => 'required|boolean',
            'graduation_date' => 'required_if:is_graduating,true|date|after_or_equal:today'
        ]);

        $isGraduating = $request->boolean('is_graduating');

        if ($isGraduating) {
            // Mark as graduating and set graduation date
            $user->update([
                'is_graduating' => true,
                'graduation_date' => $request->graduation_date,
            ]);

            $message = 'Graduation status updated successfully. You will be automatically converted to alumni status on your graduation date.';
        } else {
            // Remove graduation status
            $user->update([
                'is_graduating' => false,
                'graduation_date' => null,
            ]);

            $message = 'Graduation status removed successfully.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_graduating' => $user->is_graduating,
            'graduation_date' => $user->graduation_date?->format('M d, Y'),
        ]);
    }

    /**
     * Convert graduating student to alumni (can be called manually or via scheduled job)
     */
    public function convertToAlumni(Request $request)
    {
        $user = $request->user();

        // Check if user is a graduating student
        if ($user->account_type !== 'student' || !$user->is_graduating) {
            return response()->json([
                'success' => false,
                'message' => 'User is not eligible for alumni conversion.'
            ], 400);
        }

        // Check if graduation date has passed or is today
        if (!$user->graduation_date || $user->graduation_date->isFuture()) {
            return response()->json([
                'success' => false,
                'message' => 'Graduation date has not yet arrived.'
            ], 400);
        }

        // Convert to alumni
        $user->update([
            'account_type' => 'alumni',
            'graduated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Congratulations! Your account has been converted to alumni status.',
            'account_type' => $user->account_type,
            'graduated_at' => $user->graduated_at->format('M d, Y'),
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout(); // ✅ Logs out the user

        $request->session()->invalidate(); // ✅ Invalidates the session
        $request->session()->regenerateToken(); // ✅ Prevents CSRF attacks

        return redirect('/')->with('status', 'You have been logged out successfully.');
    }

    /**
     * Update user's email address with verification
     */
    public function updateEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'current_password' => ['required', 'string'],
        ]);

        $user = $request->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Check if email is already in use
        $emailExists = RoleAccount::where('email', $request->email)
                                 ->where('id', '!=', $user->id)
                                 ->exists();

        if ($emailExists) {
            return back()->withErrors(['email' => 'This email address is already in use.']);
        }

        // Generate verification token
        $token = Str::random(60);

        // Store pending email and token
        $user->update([
            'pending_email' => $request->email,
            'email_verification_token' => $token,
            'email_verification_sent_at' => now(),
        ]);

        // Send verification email
        $this->sendEmailVerification($user, $request->email, $token);

        return back()->with('status', 'email-verification-sent');
    }

    /**
     * Verify email change
     */
    public function verifyEmailChange(Request $request, $token)
    {
        $user = RoleAccount::where('email_verification_token', $token)->first();

        if (!$user || !$user->pending_email) {
            return redirect()->route('profile.edit')->with('error', 'Invalid verification token.');
        }

        // Check if token is not expired (24 hours)
        if ($user->email_verification_sent_at->addHours(24)->isPast()) {
            return redirect()->route('profile.edit')->with('error', 'Verification token has expired.');
        }

        // Update email
        $user->update([
            'email' => $user->pending_email,
            'pending_email' => null,
            'email_verification_token' => null,
            'email_verification_sent_at' => null,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('profile.edit')->with('status', 'email-updated');
    }

    /**
     * Send email verification
     */
    private function sendEmailVerification($user, $newEmail, $token)
    {
        $verificationUrl = route('profile.verify-email', $token);

        // Send email (you can create a proper mail class for this)
        Mail::raw(
            "Hello {$user->fullname},\n\n" .
            "You have requested to change your email address in the SPUP Good Moral Application System.\n\n" .
            "Please click the following link to verify your new email address:\n" .
            "{$verificationUrl}\n\n" .
            "This link will expire in 24 hours.\n\n" .
            "If you did not request this change, please ignore this email.\n\n" .
            "Best regards,\n" .
            "SPUP Good Moral Application System",
            function ($message) use ($newEmail, $user) {
                $message->to($newEmail)
                        ->subject('SPUP - Verify Email Address Change')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            }
        );
    }

    /**
     * Show admin profile page
     */
    public function adminProfile()
    {
        if (auth()->user()->account_type !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('profile.admin', ['user' => auth()->user()]);
    }

    /**
     * Update admin profile
     */
    public function updateAdminProfile(Request $request)
    {
        if (auth()->user()->account_type !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:male,female'],
        ]);

        $user = auth()->user();
        $user->update([
            'fullname' => $request->fullname,
            'department' => $request->department,
            'gender' => $request->gender,
        ]);

        return back()->with('status', 'profile-updated');
    }

    /**
     * Show moderator profile page
     */
    public function moderatorProfile()
    {
        if (auth()->user()->account_type !== 'moderator') {
            abort(403, 'Unauthorized access.');
        }

        return view('profile.moderator', ['user' => auth()->user()]);
    }

    /**
     * Update moderator profile
     */
    public function updateModeratorProfile(Request $request)
    {
        if (auth()->user()->account_type !== 'moderator') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:male,female'],
        ]);

        $user = auth()->user();
        $user->update([
            'fullname' => $request->fullname,
            'department' => $request->department,
            'gender' => $request->gender,
        ]);

        return back()->with('status', 'profile-updated');
    }

    /**
     * Get notification counts for PSG Officer
     */
    public function getPsgOfficerNotificationCounts()
    {
        // For now, return sample data - you can implement actual logic later
        return response()->json([
            'violatorNotifications' => 0,
            'addViolatorNotifications' => 0,
        ]);
    }

    /**
     * Get notification counts for Sec OSA
     */
    public function getSecOsaNotificationCounts()
    {
        // For now, return sample data - you can implement actual logic later
        return response()->json([
            'applicationNotifications' => 0,
            'minorViolationNotifications' => 0,
            'majorViolationNotifications' => 0,
        ]);
    }

    /**
     * Get notification counts for Head OSA
     */
    public function getHeadOsaNotificationCounts()
    {
        // For now, return sample data - you can implement actual logic later
        return response()->json([
            'applicationNotifications' => 0,
        ]);
    }

    /**
     * Get notification counts for Dean
     */
    public function getDeanNotificationCounts()
    {
        $dean = Auth::user();
        $department = $dean->department;

        // Count pending Good Moral applications that need dean approval
        $pendingApplications = \App\Models\GoodMoralApplication::where(function($query) {
            $query->where('application_status', 'LIKE', 'Approved By Registrar%')
                  ->orWhere('application_status', 'LIKE', 'Approved by Registrar%')
                  ->orWhere('application_status', '=', 'Approved By Registrar')
                  ->orWhere('application_status', '=', 'Approved by Registrar');
        })
        ->where('department', $department)
        ->whereNotNull('application_status')
        ->count();

        // Count major violations pending dean review
        $majorViolations = \App\Models\StudentViolation::where('offense_type', 'major')
            ->where('status', 0) // Pending status
            ->whereHas('student', function($query) use ($department) {
                $query->where('department', $department);
            })
            ->count();

        // Count minor violations pending dean review
        $minorViolations = \App\Models\StudentViolation::where('offense_type', 'minor')
            ->where('status', 0) // Pending status
            ->whereHas('student', function($query) use ($department) {
                $query->where('department', $department);
            })
            ->count();

        return response()->json([
            'pendingApplications' => $pendingApplications,
            'majorViolations' => $majorViolations,
            'minorViolations' => $minorViolations,
        ]);
    }

    /**
     * Get notification counts for Registrar
     */
    public function getRegistrarNotificationCounts()
    {
        // Count pending Good Moral applications that need registrar approval
        $pendingApplications = \App\Models\GoodMoralApplication::where('status', 'pending')->count();

        return response()->json([
            'pendingApplications' => $pendingApplications,
        ]);
    }
}
