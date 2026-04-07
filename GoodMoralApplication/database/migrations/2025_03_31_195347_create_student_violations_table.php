<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('student_violations', function (Blueprint $table) {
      $table->id();
      $table->string('first_name');
      $table->string('last_name');
      $table->text('violation');
      $table->text('status');
      $table->text('offense_type');
      $table->string('student_id');
      $table->string('added_by');
      $table->string('unique_id');
      $table->string('department');
      $table->string('document_path')->nullable();
      $table->string('ref_num')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('student_violations');
  }
};
