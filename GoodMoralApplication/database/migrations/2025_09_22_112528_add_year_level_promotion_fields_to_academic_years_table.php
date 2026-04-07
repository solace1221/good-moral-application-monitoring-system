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
        Schema::table('academic_years', function (Blueprint $table) {
            if (!Schema::hasColumn('academic_years', 'year_name')) {
                $table->string('year_name')->nullable();
            }
            if (!Schema::hasColumn('academic_years', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('academic_years', 'end_date')) {
                $table->date('end_date')->nullable();
            }
            if (!Schema::hasColumn('academic_years', 'year_level_promotion_active')) {
                $table->boolean('year_level_promotion_active')->default(false);
            }
            if (!Schema::hasColumn('academic_years', 'promotion_triggered_at')) {
                $table->timestamp('promotion_triggered_at')->nullable();
            }
            if (!Schema::hasColumn('academic_years', 'promotion_triggered_by')) {
                $table->string('promotion_triggered_by')->nullable();
            }
            if (!Schema::hasColumn('academic_years', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {
            $table->dropColumn([
                'year_name',
                'start_date', 
                'end_date',
                'year_level_promotion_active',
                'promotion_triggered_at',
                'promotion_triggered_by',
                'notes'
            ]);
        });
    }
};
