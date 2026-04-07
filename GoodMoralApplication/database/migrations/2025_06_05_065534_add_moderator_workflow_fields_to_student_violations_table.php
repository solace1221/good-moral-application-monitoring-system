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
            $table->timestamp('forwarded_to_admin_at')->nullable()->after('proceedings_uploaded_at');
            $table->string('forwarded_by')->nullable()->after('forwarded_to_admin_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_violations', function (Blueprint $table) {
            $table->dropColumn([
                'forwarded_to_admin_at',
                'forwarded_by'
            ]);
        });
    }
};
