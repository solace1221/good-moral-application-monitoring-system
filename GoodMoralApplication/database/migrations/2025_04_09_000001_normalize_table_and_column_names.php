<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Laravel naming convention alignment:
 *
 * 1. receipt → receipts (plural table name)
 * 2. student_year_level_history → student_year_level_histories (plural table name)
 * 3. organizations.dsn_id → organizations.id (standard primary key name)
 * 4. organizations.dept_id → organizations.department_id (standard foreign key name)
 */
return new class extends Migration
{
    public function up(): void
    {
        // -------------------------------------------------------
        // 1. Rename receipt → receipts
        // -------------------------------------------------------

        // Drop FK from good_moral_applications that references old table name
        Schema::table('good_moral_applications', function (Blueprint $table) {
            $table->dropForeign(['receipt_id']);
        });

        Schema::rename('receipt', 'receipts');

        // Re-add FK pointing to the renamed table
        Schema::table('good_moral_applications', function (Blueprint $table) {
            $table->foreign('receipt_id')
                ->references('id')
                ->on('receipts')
                ->nullOnDelete();
        });

        // -------------------------------------------------------
        // 2. Rename student_year_level_history → student_year_level_histories
        // -------------------------------------------------------

        Schema::rename('student_year_level_history', 'student_year_level_histories');

        // -------------------------------------------------------
        // 3. Rename organizations.dsn_id → organizations.id
        // -------------------------------------------------------

        Schema::table('organizations', function (Blueprint $table) {
            $table->renameColumn('dsn_id', 'id');
        });

        // -------------------------------------------------------
        // 4. Rename organizations.dept_id → organizations.department_id
        // -------------------------------------------------------

        // Drop FK added by previous migration before renaming
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['dept_id']);
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->renameColumn('dept_id', 'department_id');
        });

        // Re-add FK with new column name
        Schema::table('organizations', function (Blueprint $table) {
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // 4. Revert organizations.department_id → dept_id
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->renameColumn('department_id', 'dept_id');
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->foreign('dept_id')
                ->references('id')
                ->on('departments')
                ->nullOnDelete();
        });

        // 3. Revert organizations.id → dsn_id
        Schema::table('organizations', function (Blueprint $table) {
            $table->renameColumn('id', 'dsn_id');
        });

        // 2. Revert student_year_level_histories → student_year_level_history
        Schema::rename('student_year_level_histories', 'student_year_level_history');

        // 1. Revert receipts → receipt
        Schema::table('good_moral_applications', function (Blueprint $table) {
            $table->dropForeign(['receipt_id']);
        });

        Schema::rename('receipts', 'receipt');

        Schema::table('good_moral_applications', function (Blueprint $table) {
            $table->foreign('receipt_id')
                ->references('id')
                ->on('receipt')
                ->nullOnDelete();
        });
    }
};
