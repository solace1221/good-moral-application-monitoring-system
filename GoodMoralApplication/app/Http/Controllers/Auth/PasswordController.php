<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RoleAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $hashedPassword = Hash::make($validated['password']);

        DB::transaction(function () use ($request, $hashedPassword) {
            // 1. Update users table
            $request->user()->update([
                'password' => $hashedPassword,
            ]);

            // 2. Sync role_account table
            RoleAccount::where('email', $request->user()->email)->update([
                'password' => $hashedPassword,
            ]);
        });

        return back()->with('status', 'password-updated');
    }
}
