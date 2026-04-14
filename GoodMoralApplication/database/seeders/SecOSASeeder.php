<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RoleAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SecOSASeeder extends Seeder
{
    public function run(): void
    {
        $email = 'secosa@test.com';
        $plainPassword = 'password123';
        $hashedPassword = Hash::make($plainPassword);

        $existsInUsers = User::whereRaw('LOWER(email) = ?', [$email])->exists();
        $existsInRoleAccount = RoleAccount::whereRaw('LOWER(email) = ?', [$email])->exists();

        if ($existsInUsers && $existsInRoleAccount) {
            $this->command->info("Skipped {$email}: already exists in both tables");
            return;
        }

        DB::transaction(function () use ($email, $plainPassword, $hashedPassword, $existsInUsers, $existsInRoleAccount) {
            if (!$existsInUsers) {
                User::create([
                    'name' => 'secosa',
                    'firstname' => 'Test',
                    'lastname' => 'Sec OSA',
                    'middlename' => null,
                    'suffix_name' => null,
                    'email' => $email,
                    'password' => $plainPassword,
                    'status' => 'active',
                    'role' => 'sec_osa',
                ]);
            }

            if (!$existsInRoleAccount) {
                RoleAccount::create([
                    'fullname' => 'Test Sec OSA',
                    'email' => $email,
                    'password' => $hashedPassword,
                    'account_type' => 'sec_osa',
                    'status' => 'active',
                ]);
            }
        });

        $this->command->info("Created Sec OSA account: {$email}");
    }
}
