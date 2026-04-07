<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RoleAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TestLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test admin login credentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = 'admin@admin.com';
        $password = 'admin123';

        // Find the user
        $user = RoleAccount::where('email', $email)->first();

        if (!$user) {
            $this->error('User not found with email: ' . $email);
            return;
        }

        $this->info('User found:');
        $this->info('Email: ' . $user->email);
        $this->info('Account Type: ' . $user->account_type);
        $this->info('Status: ' . $user->status);
        $this->info('Fullname: ' . $user->fullname);

        // Test password
        if (Hash::check($password, $user->password)) {
            $this->info('✅ Password is correct!');
        } else {
            $this->error('❌ Password is incorrect!');
        }

        // Test Auth::attempt
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $this->info('✅ Auth::attempt successful!');
            Auth::logout();
        } else {
            $this->error('❌ Auth::attempt failed!');
        }
    }
}
