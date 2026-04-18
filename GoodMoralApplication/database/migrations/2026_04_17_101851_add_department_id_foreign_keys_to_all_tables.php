<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables that need a department_id FK column added.
     * role_account already has department_id but needs population.
     */
    private array $tablesToAdd = [
        'courses',
        'student_registrations',
        'student_violations',
        'good_moral_applications',
        'dean_applications',
        'head_osa_applications',
        'sec_osa_applications',
        'notifarchives',
        'archived_role_accounts',
    ];

    public function up(): void
    {
        // 1. Add department_id column + FK to tables that don't have it
        foreach ($this->tablesToAdd as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'department_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->unsignedBigInteger('department_id')->nullable()->after('id');
                    $t->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
                    $t->index('department_id');
                });
            }
        }

        // 2. Populate department_id from the existing department string column
        $allTables = array_merge(['role_account'], $this->tablesToAdd);

        foreach ($allTables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'department') && Schema::hasColumn($table, 'department_id')) {
                DB::statement("
                    UPDATE `{$table}`
                    SET department_id = (
                        SELECT id FROM departments
                        WHERE departments.department_code = `{$table}`.department
                        LIMIT 1
                    )
                    WHERE department IS NOT NULL
                      AND department_id IS NULL
                ");
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tablesToAdd as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'department_id')) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    $t->dropForeign(["{$table}_department_id_foreign"]);
                    $t->dropColumn('department_id');
                });
            }
        }
    }
};
