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
        // Check if student_registrations table exists before modifying
        if (Schema::hasTable('student_registrations')) {
            Schema::table('student_registrations', function (Blueprint $table) {
                if (!Schema::hasColumn('student_registrations', 'mname')) {
                    $table->string('mname')->nullable()->after('fname');
                }
                if (!Schema::hasColumn('student_registrations', 'extension')) {
                    $table->string('extension')->nullable()->after('lname');
                }
            });
        }

        // Check if role_account table exists before modifying
        if (Schema::hasTable('role_account')) {
            Schema::table('role_account', function (Blueprint $table) {
                if (!Schema::hasColumn('role_account', 'mname')) {
                    $table->string('mname')->nullable()->after('fullname');
                }
                if (!Schema::hasColumn('role_account', 'extension')) {
                    $table->string('extension')->nullable()->after('mname');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('student_registrations')) {
            Schema::table('student_registrations', function (Blueprint $table) {
                if (Schema::hasColumn('student_registrations', 'mname')) {
                    $table->dropColumn('mname');
                }
                if (Schema::hasColumn('student_registrations', 'extension')) {
                    $table->dropColumn('extension');
                }
            });
        }

        if (Schema::hasTable('role_account')) {
            Schema::table('role_account', function (Blueprint $table) {
                if (Schema::hasColumn('role_account', 'mname')) {
                    $table->dropColumn('mname');
                }
                if (Schema::hasColumn('role_account', 'extension')) {
                    $table->dropColumn('extension');
                }
            });
        }
    }
};
