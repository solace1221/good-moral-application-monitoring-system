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
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->text('offense_type');
            $table->text('description');
            $table->string('article')->nullable();
            $table->timestamps();
        });

        Schema::create('student_violations', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->text('violation');
            $table->text('status');
            $table->text('offense_type');
            $table->string('student_id');
            $table->string('added_by', 200);
            $table->string('unique_id');
            $table->unsignedBigInteger('violation_id')->nullable()->index();
            $table->string('department');
            $table->string('course')->nullable();
            $table->string('document_path', 500)->nullable();
            $table->date('meeting_date')->nullable();
            $table->text('meeting_notes')->nullable();
            $table->string('proceedings_uploaded_by', 200)->nullable();
            $table->timestamp('proceedings_uploaded_at')->nullable();
            $table->timestamp('forwarded_to_admin_at')->nullable();
            $table->string('forwarded_by', 200)->nullable();
            $table->string('closed_by', 200)->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->string('ref_num')->nullable();
            $table->timestamps();
            $table->boolean('downloaded')->default(false);

            $table->foreign('violation_id')->references('id')->on('violations')->nullOnDelete();
        });

        Schema::create('violation_notifs', function (Blueprint $table) {
            $table->id();
            $table->string('ref_num');
            $table->string('student_id');
            $table->string('status');
            $table->text('notif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violation_notifs');
        Schema::dropIfExists('student_violations');
        Schema::dropIfExists('violations');
    }
};
