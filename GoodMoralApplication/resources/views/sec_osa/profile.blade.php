<x-dashboard-layout>
    <x-slot name="roleTitle">SEC-OSA Moderator</x-slot>

    <x-slot name="navigation">
        <x-moderator-navigation />
    </x-slot>

    <!-- Header Section -->
    <div class="header-section">
        <div>
            <h1 class="role-title">Moderator Profile</h1>
            <p class="welcome-text">Manage your account settings and preferences</p>
            <div class="accent-line"></div>
        </div>
    </div>

    <div style="display: grid; gap: 24px;">
        <!-- Profile Information -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
                <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">
                    {{ __('Profile Information') }}
                </h2>
                <p style="margin: 8px 0 0 0; color: #6b7280; font-size: 14px;">
                    {{ __("Update your account's profile information.") }}
                </p>
            </div>
            <div style="padding: 24px;">
                <section>
                    <form method="post" action="{{ route('sec_osa.profile.update') }}" style="display: grid; gap: 20px;">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="fullname" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">{{ __('Full Name') }}</label>
                            <input type="text" id="fullname" name="fullname"
                                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                                   value="{{ old('fullname', $user->fullname) }}" required autofocus>
                            @error('fullname')
                                <p style="color: #e74c3c; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">{{ __('Email') }}</label>
                            <input type="email" id="email" name="email"
                                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; background: #f8f9fa;"
                                   value="{{ old('email', $user->email) }}" readonly>
                            <p style="color: #6b7280; font-size: 12px; margin-top: 4px;">
                                {{ __('To change your email address, use the email update section below.') }}
                            </p>
                        </div>

                        <div>
                            <label for="department" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">{{ __('Department') }}</label>
                            <input type="text" id="department" name="department"
                                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                                   value="{{ old('department', $user->department) }}" required>
                            @error('department')
                                <p style="color: #e74c3c; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gender" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">{{ __('Gender') }}</label>
                            <select id="gender" name="gender" required
                                    style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;">
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <p style="color: #e74c3c; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="account_type" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">{{ __('Account Type') }}</label>
                            <input type="text" id="account_type"
                                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; background: #f8f9fa;"
                                   value="SEC-OSA Moderator" readonly>
                        </div>

                        <div style="display: flex; align-items: center; gap: 16px;">
                            <button type="submit" class="btn-primary">{{ __('Save') }}</button>

                            @if (session('status') === 'profile-updated')
                                <p style="color: #28a745; font-size: 14px; margin: 8px 0 0 0; font-weight: 600; background: #d4edda; padding: 8px 12px; border-radius: 4px; border: 1px solid #c3e6cb;">✓ {{ __('Profile updated successfully!') }}</p>
                            @endif
                        </div>
                    </form>
                </section>
            </div>
        </div>

        <!-- Email Update Section -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
                <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">
                    {{ __('Update Email Address') }}
                </h2>
                <p style="margin: 8px 0 0 0; color: #6b7280; font-size: 14px;">
                    {{ __('Change your email address for password reset purposes. A verification email will be sent to the new address.') }}
                </p>
            </div>
            <div style="padding: 24px;">
                <section>
                    @if($user->pending_email ?? false)
                        <div style="margin-bottom: 20px; padding: 16px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <svg style="width: 20px; height: 20px; color: #856404;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <h3 style="margin: 0; color: #856404; font-size: 14px; font-weight: 600;">
                                        Email Change Pending
                                    </h3>
                                    <p style="margin: 4px 0 0 0; color: #856404; font-size: 13px;">
                                        A verification email has been sent to <strong>{{ $user->pending_email }}</strong>. Please check your email and click the verification link to complete the email change.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="post" action="{{ route('sec_osa.profile.email.update') }}" style="display: grid; gap: 20px;">
                        @csrf

                        <div>
                            <label for="current_email" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">{{ __('Current Email') }}</label>
                            <input type="email" id="current_email"
                                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; background: #f8f9fa;"
                                   value="{{ $user->email }}" readonly>
                        </div>

                        <div>
                            <label for="new_email" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">{{ __('New Email Address') }}</label>
                            <input type="email" id="new_email" name="email"
                                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <p style="color: #e74c3c; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="current_password" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">{{ __('Current Password') }}</label>
                            <input type="password" id="current_password" name="current_password"
                                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                                   required>
                            @error('current_password')
                                <p style="color: #e74c3c; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="display: flex; align-items: center; gap: 16px;">
                            <button type="submit" class="btn-primary">{{ __('Update Email') }}</button>

                            @if (session('status') === 'email-verification-sent')
                                <p style="color: #28a745; font-size: 14px; margin: 8px 0 0 0; font-weight: 600; background: #d4edda; padding: 8px 12px; border-radius: 4px; border: 1px solid #c3e6cb;">✓ {{ __('Verification email sent! Please check your new email address.') }}</p>
                            @endif

                            @if (session('status') === 'email-updated')
                                <p style="color: #28a745; font-size: 14px; margin: 8px 0 0 0; font-weight: 600; background: #d4edda; padding: 8px 12px; border-radius: 4px; border: 1px solid #c3e6cb;">✓ {{ __('Email address updated successfully!') }}</p>
                            @endif
                        </div>
                    </form>
                </section>
            </div>
        </div>

        <!-- Update Password -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="padding: 24px; border-bottom: 1px solid #e9ecef;">
                <h2 style="margin: 0; color: var(--primary-green); font-size: 1.25rem; font-weight: 600;">
                    {{ __('Update Password') }}
                </h2>
                <p style="margin: 8px 0 0 0; color: #6b7280; font-size: 14px;">
                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                </p>
            </div>
            <div style="padding: 24px;">
                <section>
                    <form method="post" action="{{ route('sec_osa.profile.password.update') }}" style="display: grid; gap: 20px;">
                        @csrf
                        @method('put')

                        <div>
                            <label for="current_password_update" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">{{ __('Current Password') }}</label>
                            <input type="password" id="current_password_update" name="current_password"
                                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                                   required>
                            @error('current_password', 'updatePassword')
                                <p style="color: #e74c3c; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">{{ __('New Password') }}</label>
                            <input type="password" id="password" name="password"
                                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                                   required>
                            @error('password', 'updatePassword')
                                <p style="color: #e74c3c; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" style="display: block; margin-bottom: 8px; font-weight: 500; color: #495057;">{{ __('Confirm Password') }}</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px;"
                                   required>
                            @error('password_confirmation', 'updatePassword')
                                <p style="color: #e74c3c; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="display: flex; align-items: center; gap: 16px;">
                            <button type="submit" class="btn-primary">{{ __('Save') }}</button>

                            @if (session('status') === 'password-updated')
                                <p style="color: #28a745; font-size: 14px; margin: 8px 0 0 0; font-weight: 600; background: #d4edda; padding: 8px 12px; border-radius: 4px; border: 1px solid #c3e6cb;">✓ {{ __('Password updated successfully!') }}</p>
                            @endif
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-dashboard-layout>