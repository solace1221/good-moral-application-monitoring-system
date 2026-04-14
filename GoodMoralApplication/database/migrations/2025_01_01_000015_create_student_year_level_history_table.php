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
        Schema::create('student_year_level_histories', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->index();
            $table->unsignedBigInteger('academic_year_id')->index();
            $table->integer('previous_year_level')->nullable();
            $table->integer('new_year_level');
            $table->enum('promotion_type', ['automatic', 'manual', 'graduation', 'repeat']);
            $table->text('reason')->nullable();
            $table->string('processed_by')->nullable();
            $table->timestamp('effective_date')->useCurrent();
            $table->timestamps();

            $table->foreign('academic_year_id')->references('id')->on('academic_years')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_year_level_histories');
    }
};
