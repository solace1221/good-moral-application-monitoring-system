<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RoleAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'admin@spup.edu.ph';
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
                    'name' => 'System Administrator',
                    'firstname' => 'System',
                    'lastname' => 'Administrator',
                    'middlename' => null,
                    'suffix_name' => null,
                    'email' => $email,
                    'password' => $plainPassword,
                    'status' => 'active',
                    'role' => 'admin',
                ]);
            }

            if (!$existsInRoleAccount) {
                RoleAccount::create([
                    'fullname' => 'System Administrator',
                    'email' => $email,
                    'password' => $hashedPassword,
                    'account_type' => 'admin',
                    'department' => 'Administration',
                    'status' => 'active',
                ]);
            }
        });

        $this->command->info("Created admin account: {$email}");
    }
}