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
        Schema::create('generated_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type'); // good_moral_applicants, residency_applicants, etc.
            $table->string('report_title'); // Human readable title
            $table->string('academic_year'); // e.g., '2024-2025'
            $table->string('time_period'); // all, daily, monthly, yearly, first_semester, etc.
            $table->string('time_period_description')->nullable(); // Human readable time period
            $table->string('filename'); // Generated filename
            $table->string('file_path')->nullable(); // Path to stored file (if we store files)
            $table->integer('total_records')->default(0); // Number of records in report
            $table->json('summary_data')->nullable(); // Summary statistics as JSON
            $table->string('generated_by'); // User who generated the report
            $table->string('generated_by_role'); // Role of the user (admin, dean, etc.)
            $table->timestamp('generated_at'); // When the report was generated
            $table->string('file_size')->nullable(); // File size in bytes
            $table->string('status')->default('completed'); // completed, failed, processing
            $table->text('error_message')->nullable(); // Error message if failed
            $table->timestamps();

            // Indexes for better performance
            $table->index(['report_type', 'academic_year']);
            $table->index(['generated_by', 'generated_at']);
            $table->index(['time_period', 'generated_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_reports');
    }
};
