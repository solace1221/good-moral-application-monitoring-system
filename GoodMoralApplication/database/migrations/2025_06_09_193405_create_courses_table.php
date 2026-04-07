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
        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code', 20)->unique(); // e.g., 'BSIT', 'BSN'
            $table->string('course_name'); // Full course name
            $table->string('department', 10); // e.g., 'SITE', 'SNAHS'
            $table->string('department_name'); // Full department name
            $table->boolean('is_active')->default(true); // Active/inactive status
            $table->text('description')->nullable(); // Optional course description
            $table->integer('sort_order')->default(0); // For ordering courses
            $table->timestamps();

            // Indexes for better performance
            $table->index('department');
            $table->index('is_active');
            $table->index(['department', 'is_active']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
