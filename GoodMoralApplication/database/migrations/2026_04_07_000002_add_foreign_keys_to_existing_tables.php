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
        // Add department_id to role_account if doesn't exist
        if (!Schema::hasColumn('role_account', 'department_id')) {
            Schema::table('role_account', function (Blueprint $table) {
                $table->foreignId('department_id')
                    ->nullable()
                    ->after('department')
                    ->constrained('departments')
                    ->nullOnDelete();
            });
        }

        // Add course_id to role_account if doesn't exist
        if (!Schema::hasColumn('role_account', 'course_id')) {
            Schema::table('role_account', function (Blueprint $table) {
                $table->foreignId('course_id')
                    ->nullable()
                    ->after('department_id')
                    ->constrained('courses')
                    ->nullOnDelete();
            });
        }

        // Add violation_id to student_violations if doesn't exist
        if (!Schema::hasColumn('student_violations', 'violation_id')) {
            Schema::table('student_violations', function (Blueprint $table) {
                $table->foreignId('violation_id')
                    ->nullable()
                    ->after('unique_id')
                    ->constrained('violations')
                    ->nullOnDelete();
            });
        }

        // Add receipt_id to good_moral_applications if doesn't exist
        if (!Schema::hasColumn('good_moral_applications', 'receipt_id')) {
            Schema::table('good_moral_applications', function (Blueprint $table) {
                $table->foreignId('receipt_id')
                    ->nullable()
                    ->after('reference_number')
                    ->constrained('receipt')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_account', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
        });

        Schema::table('student_violations', function (Blueprint $table) {
            $table->dropForeign(['violation_id']);
            $table->dropColumn('violation_id');
        });

        Schema::table('good_moral_applications', function (Blueprint $table) {
            $table->dropForeign(['receipt_id']);
            $table->dropColumn('receipt_id');
        });
    }
};
