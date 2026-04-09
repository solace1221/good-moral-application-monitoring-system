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
        Schema::create('role_account', function (Blueprint $table) {
            $table->id();
            $table->string('fullname', 200);
            $table->string('mname')->nullable();
            $table->string('extension')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('organization', 300)->nullable();
            $table->string('position', 200)->nullable();
            $table->string('email')->unique();
            $table->string('pending_email')->nullable();
            $table->string('email_verification_token')->nullable();
            $table->timestamp('email_verification_sent_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('student_id')->nullable()->unique();
            $table->string('department')->nullable();
            $table->unsignedBigInteger('department_id')->nullable()->index();
            $table->unsignedBigInteger('course_id')->nullable()->index();
            $table->string('course', 20)->nullable();
            $table->string('year_level', 50)->nullable();
            $table->string('password');
            $table->string('account_type');
            $table->tinyInteger('status')->default(1);
            $table->boolean('is_graduating')->default(false);
            $table->date('graduation_date')->nullable();
            $table->timestamp('graduated_at')->nullable();
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
            $table->foreign('course_id')->references('id')->on('courses')->nullOnDelete();
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
