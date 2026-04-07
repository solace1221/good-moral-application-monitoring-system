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
        // Add course field to student_registrations table
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->string('course', 20)->nullable()->after('department');
        });

        // Add course field to role_account table
        Schema::table('role_account', function (Blueprint $table) {
            $table->string('course', 20)->nullable()->after('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove course field from student_registrations table
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn('course');
        });

        // Remove course field from role_account table
        Schema::table('role_account', function (Blueprint $table) {
            $table->dropColumn('course');
        });
    }
};
