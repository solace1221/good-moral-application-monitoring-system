<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@spup.edu.ph'],
            [
                'name' => 'System Administrator',
                'firstname' => 'System',
                'lastname' => 'Administrator',
                'middlename' => null,
                'suffix_name' => null,
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ]
        );
    }
}