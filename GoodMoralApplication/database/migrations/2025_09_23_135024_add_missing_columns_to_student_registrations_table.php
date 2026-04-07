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
            // Add gender column after extension
            if (!Schema::hasColumn('student_registrations', 'gender')) {
                $table->string('gender')->nullable()->after('extension');
            }
            
            // Add course column after department
            if (!Schema::hasColumn('student_registrations', 'course')) {
                $table->string('course')->nullable()->after('department');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn(['gender', 'course']);
        });
    }
};
