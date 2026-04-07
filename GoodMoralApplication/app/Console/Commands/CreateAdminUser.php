<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RoleAccount;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if admin already exists
        $existingAdmin = RoleAccount::where('email', 'admin@admin.com')->first();

        if ($existingAdmin) {
            $this->info('Admin user already exists with email: admin@admin.com');
            $this->info('Current account type: ' . $existingAdmin->account_type);
            $this->info('Current status: ' . $existingAdmin->status);

            // Update password to ensure it's correct
            $existingAdmin->password = Hash::make('admin123');
            $existingAdmin->account_type = 'admin';
            $existingAdmin->status = '1';
            $existingAdmin->save();

            $this->info('Admin password has been reset to: admin123');
            return;
        }

        // Create new admin user
        $admin = RoleAccount::create([
            'email' => 'admin@admin.com',
            'student_id' => 'ADMIN001',
            'department' => 'ADMIN',
            'password' => Hash::make('admin123'),
            'account_type' => 'admin',
            'status' => '1',
            'fullname' => 'System Administrator',
        ]);

        $this->info('Admin user created successfully!');
        $this->info('Email: admin@admin.com');
        $this->info('Password: admin123');
        $this->info('Account Type: admin');
    }
}
