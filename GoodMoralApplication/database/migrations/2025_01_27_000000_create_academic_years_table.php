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
        if (!Schema::hasTable('academic_years')) {
            Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year', 20)->unique(); // e.g., '2025-2026'
            $table->integer('start_year'); // e.g., 2025
            $table->integer('end_year'); // e.g., 2026
            $table->boolean('is_active')->default(true); // Active/inactive status
            $table->boolean('is_current')->default(false); // Current academic year
            $table->text('description')->nullable(); // Optional description
            $table->integer('sort_order')->default(0); // For ordering academic years
            $table->timestamps();
            });

            // Insert default academic years
            DB::table('academic_years')->insert([
            [
                'academic_year' => '2023-2024',
                'start_year' => 2023,
                'end_year' => 2024,
                'is_active' => true,
                'is_current' => false,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'academic_year' => '2024-2025',
                'start_year' => 2024,
                'end_year' => 2025,
                'is_active' => true,
                'is_current' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'academic_year' => '2025-2026',
                'start_year' => 2025,
                'end_year' => 2026,
                'is_active' => true,
                'is_current' => false,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
