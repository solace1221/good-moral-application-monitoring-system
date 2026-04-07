<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentRegistration;
use App\Models\RoleAccount;
use App\Services\ClearanceSyncService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;


class RegisteredAccountController extends Controller
{
  protected $clearanceSyncService;

  public function __construct(ClearanceSyncService $clearanceSyncService)
  {
    $this->clearanceSyncService = $clearanceSyncService;
  }

  public function create(): View
  {
    return view('auth.admin.AdminAccount')->with('success', 'Account successfully created!');
  }

  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'fullname' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:role_account,email'],
      'department' => ['required', 'string', 'max:255'],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
      'student_id' => ['nullable', 'string', 'max:20', 'unique:role_account,student_id'],
      'course' => ['nullable', 'string', 'max:255'],
      'year_level' => ['nullable', 'string', 'max:255'],
      'account_type' => ['required', 'string', 'in:dean,sec_osa,registrar,prog_coor,psg_officer,student,alumni'],
    ]);

    // Parse fullname into parts for sync
    $nameParts = $this->parseFullname($request->fullname);

    // Hash password once to use everywhere
    $hashedPassword = Hash::make($request->password);

    // Create user in Good Moral system
    $user = RoleAccount::create([
      'fullname' => $request->fullname,
      'email' => $request->email,
      'department' => $request->department,
      'password' => $hashedPassword,
      'student_id' => $request->student_id,
      'course' => $request->course,
      'year_level' => $request->year_level,
      'status' => "1",
      'account_type' => $request->account_type,
    ]);

    // Create user in local users table for authentication (for student and alumni only)
    if (in_array($request->account_type, ['student', 'alumni'])) {
      User::create([
        'name' => strtolower($nameParts['firstname'] . '.' . $nameParts['lastname']),
        'firstname' => $nameParts['firstname'],
        'lastname' => $nameParts['lastname'],
        'middlename' => $nameParts['middlename'],
        'suffix_name' => $nameParts['extension'],
        'email' => $request->email,
        'password' => $hashedPassword,
        'role' => 'student',
        'status' => 'active',
      ]);

      // Sync to Clearance Management System
      try {
        $this->clearanceSyncService->syncUser([
          'fname' => $nameParts['firstname'],
          'lname' => $nameParts['lastname'],
          'mname' => $nameParts['middlename'],
          'extension' => $nameParts['extension'],
          'email' => $request->email,
          'password' => $hashedPassword,
          'student_id' => $request->student_id,
          'department' => $request->department,
          'course' => $request->course,
          'year_level' => $request->year_level,
          'account_type' => $request->account_type,
          'status' => '1',
        ]);

        Log::info('Account synced to Clearance System', [
          'email' => $request->email,
          'student_id' => $request->student_id,
          'account_type' => $request->account_type
        ]);
      } catch (\Exception $e) {
        Log::error('Failed to sync account to Clearance System', [
          'email' => $request->email,
          'error' => $e->getMessage()
        ]);
        // Don't fail the account creation, just log the error
      }
    }

    return redirect()->route('admin.AddAccount')->with('success', 'Account successfully created!');
  }

  /**
   * Parse fullname into component parts
   * Expected format: "LASTNAME, FIRSTNAME MIDDLENAME EXTENSION"
   */
  private function parseFullname($fullname)
  {
    $parts = [
      'firstname' => '',
      'lastname' => '',
      'middlename' => '',
      'extension' => ''
    ];

    // Split by comma to separate lastname from firstname/middle/extension
    if (strpos($fullname, ',') !== false) {
      list($lastname, $rest) = array_map('trim', explode(',', $fullname, 2));
      $parts['lastname'] = $lastname;

      // Split the rest by spaces
      $restParts = array_filter(explode(' ', $rest));
      $restParts = array_values($restParts);

      if (count($restParts) >= 1) {
        $parts['firstname'] = $restParts[0];
      }
      if (count($restParts) >= 2) {
        // Check if last part is extension (Jr., Sr., III, etc.)
        $lastPart = end($restParts);
        if (preg_match('/^(Jr\.?|Sr\.?|I{1,3}|IV|V)$/i', $lastPart)) {
          $parts['extension'] = $lastPart;
          // Middle name is everything between firstname and extension
          if (count($restParts) > 2) {
            $parts['middlename'] = implode(' ', array_slice($restParts, 1, -1));
          }
        } else {
          // No extension, middle name is everything after firstname
          $parts['middlename'] = implode(' ', array_slice($restParts, 1));
        }
      }
    } else {
      // No comma, assume format: "FIRSTNAME MIDDLENAME LASTNAME"
      $allParts = array_filter(explode(' ', $fullname));
      $allParts = array_values($allParts);

      if (count($allParts) >= 1) {
        $parts['firstname'] = $allParts[0];
      }
      if (count($allParts) >= 2) {
        $parts['lastname'] = end($allParts);
      }
      if (count($allParts) >= 3) {
        $parts['middlename'] = implode(' ', array_slice($allParts, 1, -1));
      }
    }

    return $parts;
  }
}
