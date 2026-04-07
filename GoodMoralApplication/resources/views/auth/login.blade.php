<x-guest-layout>
  <div class="form-container">
    <h2 class="form-title">Sign In</h2>
    <div class="accent-line"></div>
    <p class="form-subtitle">Access your Good Moral Application and Monitoring System account</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <!-- Email Address -->
      <div style="margin-bottom: 20px;">
        <label for="email" class="form-label">Email Address</label>
        <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}"
               required autofocus autocomplete="username" placeholder="Enter your email address">
        <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
      </div>

      <!-- Password -->
      <div style="margin-bottom: 20px;">
        <label for="password" class="form-label">Password</label>
        <input id="password" class="form-input" type="password" name="password"
               required autocomplete="current-password" placeholder="Enter your password">
        <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
      </div>

      <!-- Remember Me -->
      <div style="margin-bottom: 24px;">
        <label for="remember_me" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
          <input id="remember_me" type="checkbox" name="remember"
                 style="width: 16px; height: 16px; accent-color: var(--primary-green);">
          <span style="font-size: 14px; color: #7f8c8d;">Remember me</span>
        </label>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="form-button">
        Sign In
      </button>

      <!-- Links -->
      <div style="text-align: center; margin-top: 24px;">
        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="form-link" style="font-size: 14px;">
          Forgot your password?
        </a>
        @endif
      </div>

      <div style="text-align: center; margin-top: 16px; padding-top: 24px; border-top: 1px solid #e1e8ed;">
        <p style="color: #7f8c8d; font-size: 14px; margin-bottom: 8px;">Don't have an account?</p>
        <a href="{{ route('register') }}" class="form-link">Create an account</a>
      </div>
    </form>
  </div>
</x-guest-layout>