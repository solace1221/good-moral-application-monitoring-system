<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Safe database integrity improvements identified during architecture audit.
 *
 * 1. designations.dept_id — FK to departments.id (nullable, nullOnDelete)
 * 2. good_moral_applications.reference_number — index (used in workflow lookups)
 * 3. dean_applications.reference_number — index (joined to good_moral_applications)
 * 4. head_osa_applications.reference_number — index
 * 5. sec_osa_applications.reference_number — index
 * 6. notifarchives.student_id — index (queried per-student)
 * 7. notifarchives.reference_number — index (queried by reference)
 * 8. receipt.student_id — index (queried per-student)
 * 9. student_violations.student_id — index (queried per-student)
 * 10. violation_notifs.student_id — index (queried per-student)
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Add FK constraint: designations.dept_id → departments.id
        Schema::table('designations', function (Blueprint $table) {
            $table->foreign('dept_id')
                ->references('id')
                ->on('departments')
                ->nullOnDelete();
        });

        // 2. Index reference_number on good_moral_applications
        Schema::table('good_moral_applications', function (Blueprint $table) {
            $table->index('reference_number');
        });

        // 3. Index reference_number on dean_applications
        Schema::table('dean_applications', function (Blueprint $table) {
            $table->index('reference_number');
        });

        // 4. Index reference_number on head_osa_applications
        Schema::table('head_osa_applications', function (Blueprint $table) {
            $table->index('reference_number');
        });

        // 5. Index reference_number on sec_osa_applications
        Schema::table('sec_osa_applications', function (Blueprint $table) {
            $table->index('reference_number');
        });

        // 6-7. Indexes on notifarchives
        Schema::table('notifarchives', function (Blueprint $table) {
            $table->index('student_id');
            $table->index('reference_number');
        });

        // 8. Index receipt.student_id
        Schema::table('receipt', function (Blueprint $table) {
            $table->index('student_id');
        });

        // 9. Index student_violations.student_id
        Schema::table('student_violations', function (Blueprint $table) {
            $table->index('student_id');
        });

        // 10. Index violation_notifs.student_id
        Schema::table('violation_notifs', function (Blueprint $table) {
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::table('designations', function (Blueprint $table) {
            $table->dropForeign(['dept_id']);
        });

        Schema::table('good_moral_applications', function (Blueprint $table) {
            $table->dropIndex(['reference_number']);
        });

        Schema::table('dean_applications', function (Blueprint $table) {
            $table->dropIndex(['reference_number']);
        });

        Schema::table('head_osa_applications', function (Blueprint $table) {
            $table->dropIndex(['reference_number']);
        });

        Schema::table('sec_osa_applications', function (Blueprint $table) {
            $table->dropIndex(['reference_number']);
        });

        Schema::table('notifarchives', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
            $table->dropIndex(['reference_number']);
        });

        Schema::table('receipt', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
        });

        Schema::table('student_violations', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
        });

        Schema::table('violation_notifs', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
        });
    }
};
