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
        Schema::table('good_moral_applications', function (Blueprint $table) {
            $table->string('rejection_reason')->nullable()->after('application_status');
            $table->text('rejection_details')->nullable()->after('rejection_reason');
            $table->string('rejected_by')->nullable()->after('rejection_details');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            $table->string('action_history')->nullable()->after('rejected_at'); // Track approve/reject/reconsider actions
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('good_moral_applications', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason', 'rejection_details', 'rejected_by', 'rejected_at', 'action_history']);
        });
    }
};
