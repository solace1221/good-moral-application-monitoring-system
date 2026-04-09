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
        Schema::create('good_moral_applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number');
            $table->unsignedBigInteger('receipt_id')->nullable()->index();
            $table->string('number_of_copies');
            $table->string('student_id')->index();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('fullname');
            $table->enum('gender', ['male', 'female']);
            $table->string('department');
            $table->longText('reason');
            $table->enum('certificate_type', ['good_moral', 'residency'])->default('good_moral');
            $table->string('application_status')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('rejection_details')->nullable();
            $table->string('rejected_by', 200)->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('action_history')->nullable();
            $table->string('course_completed')->nullable();
            $table->date('graduation_date')->nullable();
            $table->boolean('is_undergraduate')->default(false);
            $table->string('last_course_year_level')->nullable();
            $table->string('last_semester_sy')->nullable();
            $table->timestamps();

            $table->foreign('receipt_id')->references('id')->on('receipt')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('good_moral_applications');
    }
};
