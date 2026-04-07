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
    Schema::create('violation_notifs', function (Blueprint $table) {
      $table->id();
      $table->string('ref_num');
      $table->string('student_id');
      $table->string('status');
      $table->string('notif');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('violation_notifs');
  }
};
