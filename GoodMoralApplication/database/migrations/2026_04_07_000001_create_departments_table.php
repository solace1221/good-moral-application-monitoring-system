<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('departments')) {
            return;
        }

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('department_code', 20)->unique();
            $table->string('department_name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('department_code');
        });

        // Insert default departments
        DB::table('departments')->insert([
            ['department_code' => 'SITE', 'department_name' => 'School of Information Technology and Engineering', 'created_at' => now(), 'updated_at' => now()],
            ['department_code' => 'SASTE', 'department_name' => 'School of Arts, Sciences, Teacher Education', 'created_at' => now(), 'updated_at' => now()],
            ['department_code' => 'SBAHM', 'department_name' => 'School of Business Administration and Hospitality Management', 'created_at' => now(), 'updated_at' => now()],
            ['department_code' => 'SNAHS', 'department_name' => 'School of Nursing and Allied Health Sciences', 'created_at' => now(), 'updated_at' => now()],
            ['department_code' => 'SOM', 'department_name' => 'School of Medicine', 'created_at' => now(), 'updated_at' => now()],
            ['department_code' => 'GRADSCH', 'department_name' => 'Graduate School', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
