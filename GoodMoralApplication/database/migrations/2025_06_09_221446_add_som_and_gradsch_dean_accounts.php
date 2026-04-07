<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add SOM and Graduate School dean accounts
        DB::table('role_account')->insertOrIgnore([
            [
                'email' => 'deansom@admin.com',
                'student_id' => 'DEAN_SOM',
                'department' => 'SOM',
                'password' => Hash::make('password123'),
                'account_type' => 'deansom',
                'status' => '1',
                'fullname' => 'sample,name',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'deangradsch@admin.com',
                'student_id' => 'DEAN_GRADSCH',
                'department' => 'GRADSCH',
                'password' => Hash::make('password123'),
                'account_type' => 'deangradsch',
                'status' => '1',
                'fullname' => 'sample,name',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the SOM and Graduate School dean accounts
        DB::table('role_account')->whereIn('student_id', ['DEAN_SOM', 'DEAN_GRADSCH'])->delete();
    }
};
