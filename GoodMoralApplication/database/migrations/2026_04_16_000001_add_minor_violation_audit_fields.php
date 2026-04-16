<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_violations', function (Blueprint $table) {
            $table->string('reviewed_by', 200)->nullable()->after('decline_reason');
            $table->string('reviewed_role', 50)->nullable()->after('reviewed_by');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_role');
            $table->string('finalized_by', 200)->nullable()->after('reviewed_at');
            $table->timestamp('finalized_at')->nullable()->after('finalized_by');
        });

        // Migrate existing minor violation statuses from numeric to named values
        DB::table('student_violations')
            ->where('offense_type', 'minor')
            ->where('status', '0')
            ->update(['status' => 'Reported']);

        DB::table('student_violations')
            ->where('offense_type', 'minor')
            ->where('status', '1')
            ->update(['status' => 'Approved']);

        DB::table('student_violations')
            ->where('offense_type', 'minor')
            ->where('status', '2')
            ->update(['status' => 'Complied']);

        DB::table('student_violations')
            ->where('offense_type', 'minor')
            ->where('status', '3')
            ->update(['status' => 'Declined']);
    }

    public function down(): void
    {
        // Revert minor violation statuses back to numeric
        DB::table('student_violations')
            ->where('offense_type', 'minor')
            ->where('status', 'Reported')
            ->update(['status' => '0']);

        DB::table('student_violations')
            ->where('offense_type', 'minor')
            ->where('status', 'Approved')
            ->update(['status' => '1']);

        DB::table('student_violations')
            ->where('offense_type', 'minor')
            ->where('status', 'Complied')
            ->update(['status' => '2']);

        DB::table('student_violations')
            ->where('offense_type', 'minor')
            ->where('status', 'Declined')
            ->update(['status' => '3']);

        Schema::table('student_violations', function (Blueprint $table) {
            $table->dropColumn(['reviewed_by', 'reviewed_role', 'reviewed_at', 'finalized_by', 'finalized_at']);
        });
    }
};
