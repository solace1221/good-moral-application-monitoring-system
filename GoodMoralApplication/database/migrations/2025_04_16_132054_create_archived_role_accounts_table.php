<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('archived_role_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->string('fullname');
            $table->string('department');
            $table->string('status')->default('0'); // Rejected status
            $table->string('account_type');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archived_role_accounts');
    }
};
