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
            $table->id(); // Auto-increment primary key
            $table->string('fname'); // First name of the student
            $table->string('lname'); // Last name of the student
            $table->string('student_id')->unique(); 
            $table->string('email')->unique(); // Email (unique)
            $table->string('password'); // Password (hashed)
            $table->string('department');
            $table->string('status'); // Password (hashed)
            $table->string('account_type')->nullable(); // Phone number (optional)
            $table->string('year_level')->nullable()->default('N/A'); // Year Level (e.g., 1st Year, 2nd Year)
            $table->timestamps(); // Created_at & updated_at
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
