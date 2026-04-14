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
        Schema::create('student_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('lname');
            $table->string('extension')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('student_id')->unique();
            $table->string('email')->unique();
            $table->string('pending_email')->nullable();
            $table->string('email_verification_token')->nullable();
            $table->timestamp('email_verification_sent_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('department');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->string('course', 20)->nullable();
            $table->string('status');
            $table->string('account_type')->nullable();
            $table->string('year_level')->nullable()->default('N/A');
            $table->string('organization')->nullable();
            $table->string('position')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_registrations');
    }
};
