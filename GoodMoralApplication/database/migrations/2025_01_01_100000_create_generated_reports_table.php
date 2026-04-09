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
            $table->string('report_type')->index();
            $table->string('report_title');
            $table->string('academic_year');
            $table->string('time_period')->index();
            $table->string('time_period_description')->nullable();
            $table->string('filename');
            $table->string('file_path')->nullable();
            $table->integer('total_records')->default(0);
            $table->longText('summary_data')->nullable();
            $table->string('generated_by')->index();
            $table->string('generated_by_role');
            $table->timestamp('generated_at')->useCurrent();
            $table->string('file_size')->nullable();
            $table->string('status')->default('completed')->index();
            $table->text('error_message')->nullable();
            $table->timestamps();
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
