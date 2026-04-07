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
            $table->string('receipt_number')->nullable()->after('id');
            $table->string('student_id')->nullable()->after('receipt_number');
            $table->decimal('amount', 10, 2)->nullable()->after('student_id');
            $table->string('payment_method')->nullable()->after('amount');
            $table->string('status')->default('pending')->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt', function (Blueprint $table) {
            $table->dropColumn(['receipt_number', 'student_id', 'amount', 'payment_method', 'status']);
        });
    }
};
