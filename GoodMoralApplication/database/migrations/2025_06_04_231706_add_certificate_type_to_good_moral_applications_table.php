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
        Schema::table('good_moral_applications', function (Blueprint $table) {
            $table->enum('certificate_type', ['good_moral', 'residency'])->default('good_moral')->after('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('good_moral_applications', function (Blueprint $table) {
            $table->dropColumn('certificate_type');
        });
    }
};
