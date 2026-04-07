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
        Schema::table('receipt', function (Blueprint $table) {
            $table->string('official_receipt_no')->nullable()->after('reference_num');
            $table->date('date_paid')->nullable()->after('official_receipt_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt', function (Blueprint $table) {
            $table->dropColumn(['official_receipt_no', 'date_paid']);
        });
    }
};
