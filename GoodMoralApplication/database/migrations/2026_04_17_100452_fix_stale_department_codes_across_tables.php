<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix stale department codes across all tables.
     *
     * Mappings:
     *   SITE       → SIE      (department was renamed)
     *   GRADSCHOOL → GRADSCH  (typo / old value)
     *   saas       → SASTE    (typo / old value)
     */
    public function up(): void
    {
        $mappings = [
            'SITE'       => 'SIE',
            'GRADSCHOOL' => 'GRADSCH',
            'saas'       => 'SASTE',
        ];

        $tables = [
            'role_account',
            'student_registrations',
            'student_violations',
            'good_moral_applications',
            'courses',
        ];

        foreach ($tables as $table) {
            foreach ($mappings as $old => $new) {
                DB::table($table)
                    ->where('department', $old)
                    ->update(['department' => $new]);
            }
        }
    }

    /**
     * Reverse the mappings.
     */
    public function down(): void
    {
        $mappings = [
            'SIE'    => 'SITE',
            // GRADSCH and SASTE are also valid original values for other rows,
            // so we cannot safely reverse those without tracking which rows were changed.
        ];

        $tables = [
            'role_account',
            'student_registrations',
            'student_violations',
            'good_moral_applications',
            'courses',
        ];

        foreach ($tables as $table) {
            foreach ($mappings as $old => $new) {
                DB::table($table)
                    ->where('department', $old)
                    ->update(['department' => $new]);
            }
        }
    }
};
