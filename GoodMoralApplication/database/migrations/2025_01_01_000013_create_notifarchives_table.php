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
        Schema::create('notifarchives', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->index();
            $table->string('number_of_copies');
            $table->string('student_id')->index();
            $table->string('status');
            $table->string('fullname', 200);
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('department');
            $table->longText('reason');
            $table->enum('certificate_type', ['good_moral', 'residency'])->default('good_moral');
            $table->string('application_status')->nullable();
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
        Schema::dropIfExists('notifarchives');
    }
};
