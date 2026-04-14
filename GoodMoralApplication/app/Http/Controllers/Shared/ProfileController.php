<?php

namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateGraduationStatusRequest;
use App\Http\Requests\UpdateEmailWithPasswordRequest;
use App\Http\Requests\UpdateStaffProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Models\RoleAccount;
use App\Models\StudentRegistration;
use App\Models\User;
use App\Services\NotificationCountService;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $roleAccount = RoleAccount::where('email', $user->email)->first();
        $viewName = $this->getProfileViewName($user->account_type);

        return view($viewName, [
            'user' => $user,
            'student' => $roleAccount,
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
            'deansom' => 'profile.dean',
            'deangradsch' => 'profile.dean',
            'prog_coor' => 'profile.prog_coor',
            'registrar' => 'profile.registrar',
            'student' => 'student.profile',
            'alumni' => 'student.profile',
            'psg_officer' => 'profile.psg_officer',
            'sec_osa' => 'profile.sec_osa',
        ];

        return $roleViews[$accountType] ?? 'profile.edit';
    }

    /**
     * Update the user's profile information (role-aware).
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $roleAccount = RoleAccount::where('email', $user->email)->first();

        if (!$roleAccount) {
            return back()->with('error', 'Account record not found.');
        }

        $accountType = $user->account_type;

        // Student / Alumni → limited profile (email + gender only)
        if (in_array($accountType, ['student', 'alumni'])) {
            return $this->updateStudentProfile($request, $user, $roleAccount);
        }

        // PSG Officer → name parts + org/position
        if ($accountType === 'psg_officer') {
            return $this->updatePsgOfficerProfile($request, $user, $roleAccount);
        }

        // Staff roles → fullname + department + gender
        $staffRoles = ['admin', 'moderator', 'sec_osa', 'dean', 'deansom', 'deangradsch', 'registrar', 'prog_coor'];
        if (in_array($accountType, $staffRoles)) {
            return $this->updateStaffProfileData($request, $user, $roleAccount);
        }

        // Unknown role — log and reject
        Log::warning('Profile update attempted by unknown role', [
            'user_id' => $user->id,
            'account_type' => $accountType,
        ]);

        return back()->with('error', 'Unable to update profile for this account type.');
    }

    private function updateStudentProfile(Request $request, User $user, RoleAccount $roleAccount): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:role_account,email,' . $roleAccount->id],
            'gender' => ['required', 'string', 'in:male,female'],
        ]);

        $oldEmail = $user->email;

        // Log attempted unauthorized field changes
        $restrictedFields = ['first_name', 'middle_name', 'last_name', 'extension', 'course', 'year_level'];
        $attemptedChanges = array_intersect(array_keys($request->all()), $restrictedFields);
        if (!empty($attemptedChanges)) {
            Log::warning('Unauthorized profile update attempt', [
                'user_id' => $user->id,
                'student_id' => $roleAccount->student_id,
                'attempted_fields' => $attemptedChanges,
                'ip_address' => $request->ip(),
            ]);
        }

        DB::transaction(function () use ($user, $roleAccount, $request, $oldEmail) {
            $user->update(['email' => $request->email]);

            $roleAccount->update([
                'email' => $request->email,
                'gender' => $request->gender,
            ]);

            if ($request->email !== $oldEmail) {
                StudentRegistration::where('email', $oldEmail)->update([
                    'email' => $request->email,
                ]);
            }
        });

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    private function updatePsgOfficerProfile(Request $request, User $user, RoleAccount $roleAccount): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z\s]*$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'extension' => ['nullable', 'string', 'max:10', 'regex:/^[A-Za-z\s]*$/'],
            'email' => ['required', 'email', 'max:255', 'unique:role_account,email,' . $roleAccount->id],
            'gender' => ['required', 'string', 'in:male,female'],
            'organization' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
        ]);

        $oldEmail = $user->email;
        $fullname = $request->last_name . ', ' . $request->first_name;
        if ($request->middle_name) {
            $fullname .= ' ' . $request->middle_name;
        }

        DB::transaction(function () use ($user, $roleAccount, $request, $oldEmail, $fullname) {
            $user->update([
                'firstname' => $request->first_name,
                'lastname' => $request->last_name,
                'middlename' => $request->middle_name,
                'suffix_name' => $request->extension,
                'email' => $request->email,
            ]);

            $roleAccount->update([
                'fullname' => $fullname,
                'mname' => $request->middle_name,
                'extension' => $request->extension,
                'email' => $request->email,
                'gender' => $request->gender,
                'organization' => $request->organization,
                'position' => $request->position,
            ]);
        });

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    private function updateStaffProfileData(Request $request, User $user, RoleAccount $roleAccount): RedirectResponse
    {
        // Validate using UpdateStaffProfileRequest (resolving from container triggers validation)
        app(UpdateStaffProfileRequest::class);

        DB::transaction(function () use ($user, $roleAccount, $request) {
            // Sync users.name with fullname
            $user->update(['name' => $request->fullname]);

            // Update role_account with profile fields
            $roleAccount->update([
                'fullname' => $request->fullname,
                'department' => $request->department,
                'gender' => $request->gender,
            ]);
        });

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
    public function updateGraduationStatus(UpdateGraduationStatusRequest $request)
    {
        $user = $request->user();

        // Only students can update their graduation status
        if ($user->account_type !== 'student') {
            return response()->json([
                'success' => false,
                'message' => 'Only students can update graduation status.'
            ], 403);
        }

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
    /**
     * Update user's email address with verification
     */
    public function updateEmail(UpdateEmailWithPasswordRequest $request): RedirectResponse
    {

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

        $oldEmail = $user->email;
        $newEmail = $user->pending_email;

        DB::transaction(function () use ($user, $oldEmail, $newEmail) {
            // 1. Update role_account table
            $user->update([
                'email' => $newEmail,
                'pending_email' => null,
                'email_verification_token' => null,
                'email_verification_sent_at' => null,
                'email_verified_at' => now(),
            ]);

            // 2. Sync users table
            User::where('email', $oldEmail)->update([
                'email' => $newEmail,
            ]);

            // 3. Sync student_registrations table
            StudentRegistration::where('email', $oldEmail)->update([
                'email' => $newEmail,
            ]);
        });

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
    public function getDeanNotificationCounts(NotificationCountService $notificationCountService)
    {
        $department = Auth::user()->department;

        return response()->json($notificationCountService->getDeanCounts($department));
    }

    /**
     * Get notification counts for Registrar
     */
    public function getRegistrarNotificationCounts(NotificationCountService $notificationCountService)
    {
        return response()->json($notificationCountService->getRegistrarCounts());
    }
}
