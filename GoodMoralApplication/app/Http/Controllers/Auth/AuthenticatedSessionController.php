<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
  /**
   * Display the login view.
   */
  public function create(): View
  {
    return view('auth.login');
  }

  /**
   * Handle an incoming authentication request.
   */
  public function store(LoginRequest $request): RedirectResponse
  {
    try {
      $request->authenticate();

      $request->session()->regenerate();

      $user = Auth::user();

      // Debug login process
      Log::info('Login process debug', [
        'user_id' => $user->id,
        'user_email' => $user->email,
        'user_account_type' => $user->account_type,
        'user_class' => get_class($user)
      ]);

      $redirectUrl = $this->redirectBasedOnRole($user);
      Log::info('Redirecting to: ' . $redirectUrl);

      return redirect($redirectUrl);
    } catch (\Exception $e) {
      Log::error('Login error: ' . $e->getMessage(), [
        'trace' => $e->getTraceAsString()
      ]);

      return redirect()->route('login')->withErrors([
        'email' => 'Login failed: ' . $e->getMessage(),
      ]);
    }
  }
  protected function isRoleAllowed($user): bool
  {
    return in_array($user->account_type, [
      'admin',
      'psg_officer',
      'dean',
      'registrar',
      'sec_osa',
      'student',
      'alumni'
    ]);
  }
  /**
   * Redirect based on the user's role.
   */
  protected function redirectBasedOnRole($user)
  {
    // Debug: Let's see what's happening
    Log::info('Login redirect debug', [
      'user_id' => $user->id,
      'account_type' => $user->account_type,
      'email' => $user->email
    ]);

    // Restore role-based redirects with explicit admin handling
    switch ($user->account_type) {
      case 'admin':
        Log::info('Redirecting admin to dashboard', ['route' => route('admin.dashboard')]);
        return route('admin.dashboard');
      case 'psg_officer':
        return route('PsgOfficer.dashboard');
      case 'dean':
        return route('dean.dashboard');
      case 'registrar':
        return route('registrar.goodMoralApplication');
      case 'sec_osa':
        return route('sec_osa.dashboard');
      case 'alumni':
      case 'student':
        return route('dashboard');
      case 'prog_coor':
        return route('prog_coor.major');
      default:
        Log::warning('Unknown account type, using default dashboard', ['account_type' => $user->account_type]);
        return route('dashboard');
    }
  }

  /**
   * Destroy an authenticated session.
   */
  public function destroy(Request $request): RedirectResponse
  {
    Session::flush();
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
  }
}
