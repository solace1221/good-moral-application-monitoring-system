<x-guest-layout>
  <div class="form-container">
    <h2 class="form-title">Reset Password</h2>
    <div class="accent-line"></div>
    <p class="form-subtitle">Enter your email address and we'll send you a password reset link</p>

    @if (!session('status'))
      <div style="background-color: #e8f4fd; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
        <strong>📧 How it works:</strong><br>
        1. Enter your registered email address below<br>
        2. Click "Send Reset Link" to receive an email<br>
        3. Check your inbox for the password reset email<br>
        4. Click the link in the email to create a new password<br>
        <br>
        <small><strong>Note:</strong> Make sure to check your spam folder if you don't see the email within a few minutes.</small>
      </div>
    @endif

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (session('status'))
      <div style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <strong>✅ Email Sent Successfully!</strong><br>
        We have sent a password reset link to your email address. Please check your inbox and follow the instructions to reset your password.
        <br><br>
        <small><strong>Note:</strong> The reset link will expire in 60 minutes. If you don't see the email, please check your spam folder.</small>
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf

      <!-- Email Address -->
      <div style="margin-bottom: 24px;">
        <label for="email" class="form-label">Email Address</label>
        <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}"
               required autofocus placeholder="Enter your email address">
        <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
      </div>

      <!-- Submit Button -->
      <button type="submit" class="form-button">
        Send Reset Link
      </button>

      <!-- Links -->
      <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e1e8ed;">
        <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 8px;">Remember your password?</p>
        <a href="{{ route('login') }}" class="form-link">Back to Sign In</a>
      </div>
    </form>
  </div>
</x-guest-layout>