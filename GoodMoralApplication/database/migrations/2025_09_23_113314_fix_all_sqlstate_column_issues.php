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
        // Fix violation_notifs table - change notif from string to text
        if (Schema::hasTable('violation_notifs') && Schema::hasColumn('violation_notifs', 'notif')) {
            Schema::table('violation_notifs', function (Blueprint $table) {
                $table->text('notif')->change(); // Allow longer notification messages
            });
        }

        // Fix student_violations table - increase column sizes for potential long data
        if (Schema::hasTable('student_violations')) {
            Schema::table('student_violations', function (Blueprint $table) {
                // Increase first_name, last_name to handle long names
                if (Schema::hasColumn('student_violations', 'first_name')) {
                    $table->string('first_name', 100)->change();
                }
                if (Schema::hasColumn('student_violations', 'last_name')) {
                    $table->string('last_name', 100)->change();
                }
                // Increase added_by to handle long full names
                if (Schema::hasColumn('student_violations', 'added_by')) {
                    $table->string('added_by', 200)->change();
                }
                // Increase document_path for long file paths
                if (Schema::hasColumn('student_violations', 'document_path')) {
                    $table->string('document_path', 500)->nullable()->change();
                }
                // Other potentially problematic fields
                if (Schema::hasColumn('student_violations', 'proceedings_uploaded_by')) {
                    $table->string('proceedings_uploaded_by', 200)->nullable()->change();
                }
                if (Schema::hasColumn('student_violations', 'forwarded_by')) {
                    $table->string('forwarded_by', 200)->nullable()->change();
                }
                if (Schema::hasColumn('student_violations', 'closed_by')) {
                    $table->string('closed_by', 200)->nullable()->change();
                }
            });
        }

        // Fix good_moral_applications table
        if (Schema::hasTable('good_moral_applications')) {
            Schema::table('good_moral_applications', function (Blueprint $table) {
                // Increase rejection_reason for long rejection messages
                if (Schema::hasColumn('good_moral_applications', 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable()->change();
                }
                // Increase rejection_details for long details
                if (Schema::hasColumn('good_moral_applications', 'rejection_details')) {
                    $table->text('rejection_details')->nullable()->change();
                }
                // Increase rejected_by for long names
                if (Schema::hasColumn('good_moral_applications', 'rejected_by')) {
                    $table->string('rejected_by', 200)->nullable()->change();
                }
                // Increase action_history for long history
                if (Schema::hasColumn('good_moral_applications', 'action_history')) {
                    $table->text('action_history')->nullable()->change();
                }
            });
        }

        // Fix role_account table
        if (Schema::hasTable('role_account')) {
            Schema::table('role_account', function (Blueprint $table) {
                // Increase fullname for long names
                if (Schema::hasColumn('role_account', 'fullname')) {
                    $table->string('fullname', 200)->change();
                }
                // Increase organization for long organization names
                if (Schema::hasColumn('role_account', 'organization')) {
                    $table->string('organization', 300)->nullable()->change();
                }
                // Increase position for long position titles
                if (Schema::hasColumn('role_account', 'position')) {
                    $table->string('position', 200)->nullable()->change();
                }
            });
        }

        // Fix receipt table
        if (Schema::hasTable('receipt')) {
            Schema::table('receipt', function (Blueprint $table) {
                // Increase document_path for long file paths
                if (Schema::hasColumn('receipt', 'document_path')) {
                    $table->string('document_path', 500)->nullable()->change();
                }
                // Increase reference_num for long reference numbers
                if (Schema::hasColumn('receipt', 'reference_num')) {
                    $table->string('reference_num', 100)->nullable()->change();
                }
                // Increase receipt_number for long receipt numbers
                if (Schema::hasColumn('receipt', 'receipt_number')) {
                    $table->string('receipt_number', 100)->nullable()->change();
                }
                // Increase official_receipt_no for long official receipt numbers
                if (Schema::hasColumn('receipt', 'official_receipt_no')) {
                    $table->string('official_receipt_no', 100)->nullable()->change();
                }
            });
        }

        // Fix archived_role_accounts table
        if (Schema::hasTable('archived_role_accounts')) {
            Schema::table('archived_role_accounts', function (Blueprint $table) {
                // Increase fullname for long names
                if (Schema::hasColumn('archived_role_accounts', 'fullname')) {
                    $table->string('fullname', 200)->change();
                }
            });
        }

        // Fix notifarchive table
        if (Schema::hasTable('notifarchives')) {
            Schema::table('notifarchives', function (Blueprint $table) {
                // Increase fullname for long names
                if (Schema::hasColumn('notifarchives', 'fullname')) {
                    $table->string('fullname', 200)->change();
                }
            });
        }

        // Fix courses table if it exists
        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                // Increase course_name for long course names
                if (Schema::hasColumn('courses', 'course_name')) {
                    $table->string('course_name', 300)->change();
                }
                // Increase department_name for long department names
                if (Schema::hasColumn('courses', 'department_name')) {
                    $table->string('department_name', 200)->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Reverting these changes might cause data loss if longer data exists
        // Only implement reversal for critical cases
        
        if (Schema::hasTable('violation_notifs') && Schema::hasColumn('violation_notifs', 'notif')) {
            Schema::table('violation_notifs', function (Blueprint $table) {
                $table->string('notif')->change(); // Revert back to string (may cause data truncation)
            });
        }
    }
};
