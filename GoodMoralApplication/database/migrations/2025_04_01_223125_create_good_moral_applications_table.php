<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodMoralApplicationsTable extends Migration
{
  public function up()
  {
    Schema::create('good_moral_applications', function (Blueprint $table) {
      $table->id();
      $table->string('reference_number');
      $table->string('number_of_copies');
      $table->string('student_id'); // Use string() to store alphanumeric student_id
      $table->foreign('student_id')->references('student_id')->on('role_account')->onDelete('cascade');
      $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
      $table->string('fullname');
      $table->string('department');
      $table->string('reason');
      $table->string('application_status')->nullable();
      $table->string('course_completed')->nullable();
      $table->date('graduation_date')->nullable();
      $table->boolean('is_undergraduate')->default(false);
      $table->string('last_course_year_level')->nullable();
      $table->string('last_semester_sy')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('good_moral_applications');
  }
}
