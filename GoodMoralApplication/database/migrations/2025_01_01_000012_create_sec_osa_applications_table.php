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
        Schema::create('sec_osa_applications', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->index();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('department');
            $table->string('fullname');
            $table->string('reason');
            $table->string('reference_number');
            $table->string('number_of_copies');
            $table->string('course_completed')->nullable();
            $table->date('graduation_date')->nullable();
            $table->boolean('is_undergraduate')->default(false);
            $table->string('last_course_year_level')->nullable();
            $table->string('last_semester_sy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sec_osa_applications');
    }
};
