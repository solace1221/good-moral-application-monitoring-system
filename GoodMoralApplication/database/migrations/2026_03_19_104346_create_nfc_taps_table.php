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
        if (Schema::hasTable('nfc_taps')) {
            return;
        }

        Schema::create('nfc_taps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('clearance_id')
                ->nullable()
                ->constrained('clearances')
                ->nullOnDelete();

            $table->foreignId('tapped_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('uid', 64);
            $table->timestamp('tapped_at')->useCurrent();

            $table->timestamps();

            $table->index('uid');
            $table->index(['student_id', 'tapped_at']);
            $table->index(['uid', 'tapped_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nfc_taps');
    }
};
