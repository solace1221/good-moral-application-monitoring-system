<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RoleAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = 'password';

        $deans = [
            ['dept' => 'SASTE', 'email' => 'dean.saste@spup.edu.ph'],
            ['dept' => 'SBAHM', 'email' => 'dean.sbahm@spup.edu.ph'],
            ['dept' => 'SITE', 'email' => 'dean.site@spup.edu.ph'],
            ['dept' => 'SNAHS', 'email' => 'dean.snahs@spup.edu.ph'],
            ['dept' => 'GRADSCHOOL', 'email' => 'dean.grad@spup.edu.ph'],
        ];

        // Hash once for role_account (which has no 'hashed' cast)
        $hashedPassword = Hash::make($defaultPassword);

        foreach ($deans as $dean) {
            $email = strtolower($dean['email']);
            $fullname = 'Dean ' . $dean['dept'];

            $existsInUsers = User::whereRaw('LOWER(email) = ?', [$email])->exists();
            $existsInRoleAccount = RoleAccount::whereRaw('LOWER(email) = ?', [$email])->exists();

            // Skip if account already exists in both tables
            if ($existsInUsers && $existsInRoleAccount) {
                $this->command->info("Skipped {$email}: already exists in both tables");
                continue;
            }

            DB::transaction(function () use ($dean, $email, $fullname, $defaultPassword, $hashedPassword, $existsInUsers, $existsInRoleAccount) {
                // 1. Login record in users table
                //    User model has 'password' => 'hashed' cast, so pass plain-text
                if (!$existsInUsers) {
                    User::create([
                        'name' => strtolower('dean.' . $dean['dept']),
                        'firstname' => 'Dean',
                        'middlename' => '',
                        'lastname' => $dean['dept'],
                        'suffix_name' => '',
                        'email' => $email,
                        'password' => $defaultPassword,
                        'status' => 'active',
                        'role' => 'dean',
                    ]);
                }

                // 2. Profile record in role_account
                //    RoleAccount has no 'hashed' cast, so pass pre-hashed password
                if (!$existsInRoleAccount) {
                    RoleAccount::create([
                        'fullname' => $fullname,
                        'email' => $email,
                        'password' => $hashedPassword,
                        'account_type' => 'dean',
                        'department' => $dean['dept'],
                        'status' => '1',
                    ]);
                }
            });

            $this->command->info("Created dean account: {$email}" .
                ($existsInUsers ? ' (backfilled role_account)' : '') .
                ($existsInRoleAccount ? ' (backfilled users)' : ''));
        }
    }
}