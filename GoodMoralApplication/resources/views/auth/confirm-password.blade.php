<x-guest-layout>
  <div class="form-container">
    <h2 class="form-title">Confirm Password</h2>
    <div class="accent-line"></div>
    <p class="form-subtitle">This is a secure area. Please confirm your password before continuing.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
      @csrf

      <!-- Password -->
      <div style="margin-bottom: 24px;">
        <label for="password" class="form-label">Password</label>
        <input id="password" class="form-input" type="password" name="password"
               required autocomplete="current-password" placeholder="Enter your password">
        <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: #e74c3c; font-size: 12px;" />
      </div>

      <!-- Submit Button -->
      <button type="submit" class="form-button">
        Confirm
      </button>
    </form>
  </div>
</x-guest-layout>