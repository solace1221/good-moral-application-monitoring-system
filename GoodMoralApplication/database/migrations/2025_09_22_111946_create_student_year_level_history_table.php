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
        Schema::create('student_year_level_history', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->integer('previous_year_level')->nullable();
            $table->integer('new_year_level');
            $table->enum('promotion_type', ['automatic', 'manual', 'graduation', 'repeat']);
            $table->text('reason')->nullable();
            $table->string('processed_by')->nullable(); // Admin who processed
            $table->timestamp('effective_date');
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('role_account')->onDelete('cascade');
            $table->index(['student_id', 'academic_year_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_year_level_history');
    }
};
