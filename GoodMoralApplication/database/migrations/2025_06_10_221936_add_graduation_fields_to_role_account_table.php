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
            $table->boolean('is_graduating')->default(false)->after('status');
            $table->date('graduation_date')->nullable()->after('is_graduating');
            $table->timestamp('graduated_at')->nullable()->after('graduation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_account', function (Blueprint $table) {
            $table->dropColumn(['is_graduating', 'graduation_date', 'graduated_at']);
        });
    }
};
