<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status', 20)->default('active')->after('password');
            }
            
            // Add role column if it doesn't exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 50)->nullable()->after('status');
            }
            
            // Add name fields if they don't exist
            if (!Schema::hasColumn('users', 'firstname')) {
                $table->string('firstname')->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('users', 'lastname')) {
                $table->string('lastname')->nullable()->after('firstname');
            }
            
            if (!Schema::hasColumn('users', 'middlename')) {
                $table->string('middlename')->nullable()->after('lastname');
            }
            
            if (!Schema::hasColumn('users', 'suffix_name')) {
                $table->string('suffix_name', 10)->nullable()->after('middlename');
            }
        });

        // Set existing users to active status
        DB::table('users')->update(['status' => 'active']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'role',
                'firstname',
                'lastname',
                'middlename',
                'suffix_name',
            ]);
        });
    }
};
