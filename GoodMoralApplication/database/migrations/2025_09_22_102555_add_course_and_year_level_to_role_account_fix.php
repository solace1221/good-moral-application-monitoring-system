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
        Schema::table('role_account', function (Blueprint $table) {
            // Add course column if it doesn't exist
            if (!Schema::hasColumn('role_account', 'course')) {
                $table->string('course', 20)->nullable()->after('department');
            }
            
            // Add year_level column if it doesn't exist
            if (!Schema::hasColumn('role_account', 'year_level')) {
                $table->string('year_level', 50)->nullable()->after('course');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_account', function (Blueprint $table) {
            $table->dropColumn(['course', 'year_level']);
        });
    }
};
