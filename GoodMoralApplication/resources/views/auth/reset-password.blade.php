<x-guest-layout>
  <div class="form-container">
    <h2 class="form-title">Reset Password</h2>
    <div class="accent-line"></div>
    <p class="form-subtitle">Enter your new password below</p>

    <form method="POST" action="{{ route('password.store') }}">
      @csrf

      <!-- Password Reset Token -->
      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      <!-- Email Address -->
      <div style="margin-bottom: 20px;">
        <label for="email" class="form-label">Email Address</label>
        <input id="email" class="form-input" type="email" name="email"
               value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
               placeholder="Enter your email address">
        <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
      </div>

      <!-- Password -->
      <div style="margin-bottom: 20px;">
        <label for="password" class="form-label">New Password</label>
        <input id="password" class="form-input" type="password" name="password"
               required autocomplete="new-password" placeholder="Enter new password">
        <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
      </div>

      <!-- Confirm Password -->
      <div style="margin-bottom: 24px;">
        <label for="password_confirmation" class="form-label">Confirm New Password</label>
        <input id="password_confirmation" class="form-input" type="password" name="password_confirmation"
               required autocomplete="new-password" placeholder="Confirm new password">
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
      </div>

      <!-- Submit Button -->
      <button type="submit" class="form-button">
        Reset Password
      </button>

      <!-- Links -->
      <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e1e8ed;">
        <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 8px;">Remember your password?</p>
        <a href="{{ route('login') }}" class="form-link">Back to Sign In</a>
      </div>
    </form>
  </div>
</x-guest-layout>