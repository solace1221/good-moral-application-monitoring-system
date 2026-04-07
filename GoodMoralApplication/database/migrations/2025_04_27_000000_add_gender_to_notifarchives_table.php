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
        if (Schema::hasTable('notifarchives')) {
            Schema::table('notifarchives', function (Blueprint $table) {
                if (!Schema::hasColumn('notifarchives', 'gender')) {
                    $table->enum('gender', ['male', 'female'])->nullable()->after('fullname');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notifarchives')) {
            Schema::table('notifarchives', function (Blueprint $table) {
                if (Schema::hasColumn('notifarchives', 'gender')) {
                    $table->dropColumn('gender');
                }
            });
        }
    }
};
