<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add gender field to student_registrations table
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female'])->nullable()->after('extension');
        });

        // Add gender field to role_account table
        Schema::table('role_account', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female'])->nullable()->after('extension');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove gender field from student_registrations table
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn('gender');
        });

        // Remove gender field from role_account table
        Schema::table('role_account', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }
};
