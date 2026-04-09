<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    public function run(): void
    {

        $deans = [
            ['dept' => 'SASTE', 'email' => 'dean.saste@spup.edu.ph'],
            ['dept' => 'SBAHM', 'email' => 'dean.sbahm@spup.edu.ph'],
            ['dept' => 'SITE', 'email' => 'dean.site@spup.edu.ph'],
            ['dept' => 'SNAHS', 'email' => 'dean.snahs@spup.edu.ph'],
            ['dept' => 'GRADSCHOOL', 'email' => 'dean.grad@spup.edu.ph'],
        ];

        foreach ($deans as $dean) {
            User::create([
                'name' => 'Dean '.$dean['dept'],
                'firstname' => 'Dean',
                'middlename' => '',
                'lastname' => $dean['dept'],
                'suffix_name' => '',
                'email' => $dean['email'],
                'password' => Hash::make('password'),
                'status' => 'active',
                'role' => 'dean'
            ]);
        }

    }
}