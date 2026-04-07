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
            if (!Schema::hasColumn('role_account', 'gender')) {
                $table->string('gender')->nullable()->after('department');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_account', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }
};
