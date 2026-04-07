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
        Schema::table('student_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('student_registrations', 'mname')) {
                $table->string('mname')->nullable()->after('fname');
            }
            if (!Schema::hasColumn('student_registrations', 'extension')) {
                $table->string('extension')->nullable()->after('lname');
            }
            if (!Schema::hasColumn('student_registrations', 'gender')) {
                $table->string('gender')->nullable()->after('account_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn(['mname', 'extension', 'gender']);
        });
    }
};
