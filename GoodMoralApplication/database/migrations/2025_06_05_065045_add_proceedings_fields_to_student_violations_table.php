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
        Schema::table('student_violations', function (Blueprint $table) {
            $table->date('meeting_date')->nullable()->after('document_path');
            $table->text('meeting_notes')->nullable()->after('meeting_date');
            $table->string('proceedings_uploaded_by')->nullable()->after('meeting_notes');
            $table->timestamp('proceedings_uploaded_at')->nullable()->after('proceedings_uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_violations', function (Blueprint $table) {
            $table->dropColumn([
                'meeting_date',
                'meeting_notes',
                'proceedings_uploaded_by',
                'proceedings_uploaded_at'
            ]);
        });
    }
};
