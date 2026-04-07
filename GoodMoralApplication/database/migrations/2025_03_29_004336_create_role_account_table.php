<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('role_account', function (Blueprint $table) {
            $table->id(); // Automatically creates the 'id' field
            $table->string('fullname')->nullable();
            $table->string('email')->unique();
            $table->string('student_id')->nullable()->unique();
            $table->string('department')->nullable();
            $table->string('password');
            $table->string('account_type');
            $table->boolean('status')->default(1); // Active by default
            $table->timestamps(); // This will create created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_account');
    }
};
