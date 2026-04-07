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
        Schema::table('role_account', function (Blueprint $table) {
            $table->string('pending_email')->nullable()->after('email');
            $table->string('email_verification_token')->nullable()->after('pending_email');
            $table->timestamp('email_verification_sent_at')->nullable()->after('email_verification_token');
            $table->timestamp('email_verified_at')->nullable()->after('email_verification_sent_at');
        });

        Schema::table('student_registrations', function (Blueprint $table) {
            $table->string('pending_email')->nullable()->after('email');
            $table->string('email_verification_token')->nullable()->after('pending_email');
            $table->timestamp('email_verification_sent_at')->nullable()->after('email_verification_token');
            $table->timestamp('email_verified_at')->nullable()->after('email_verification_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_account', function (Blueprint $table) {
            $table->dropColumn([
                'pending_email',
                'email_verification_token',
                'email_verification_sent_at',
                'email_verified_at'
            ]);
        });

        Schema::table('student_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'pending_email',
                'email_verification_token',
                'email_verification_sent_at',
                'email_verified_at'
            ]);
        });
    }
};
