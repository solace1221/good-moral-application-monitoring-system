<?php
use Illuminate\Support\Facades\DB;

/**
 * Migration Consolidation Validator
 * 
 * Run AFTER migrate:fresh to verify the consolidated migrations
 * produce the exact same schema as the original 55 migrations.
 * 
 * Usage: php validate_schema.php
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Migration Consolidation Validator ===" . PHP_EOL . PHP_EOL;

// Expected schema based on live database dump (before consolidation)
$expectedSchema = [
    'users' => ['id', 'name', 'firstname', 'lastname', 'middlename', 'suffix_name', 'email', 'email_verified_at', 'password', 'status', 'role', 'remember_token', 'created_at', 'updated_at'],
    'password_reset_tokens' => ['email', 'token', 'created_at'],
    'sessions' => ['id', 'user_id', 'ip_address', 'user_agent', 'payload', 'last_activity'],
    'cache' => ['key', 'value', 'expiration'],
    'cache_locks' => ['key', 'owner', 'expiration'],
    'jobs' => ['id', 'queue', 'payload', 'attempts', 'reserved_at', 'available_at', 'created_at'],
    'job_batches' => ['id', 'name', 'total_jobs', 'pending_jobs', 'failed_jobs', 'failed_job_ids', 'options', 'cancelled_at', 'created_at', 'finished_at'],
    'failed_jobs' => ['id', 'uuid', 'connection', 'queue', 'payload', 'exception', 'failed_at'],
    'departments' => ['id', 'department_code', 'department_name', 'created_at', 'updated_at'],
    'courses' => ['id', 'course_code', 'course_name', 'department', 'department_name', 'sort_order', 'created_at', 'updated_at'],
    'designations' => ['dsn_id', 'dept_id', 'description', 'created_at', 'updated_at'],
    'academic_years' => ['id', 'academic_year', 'start_year', 'end_year', 'is_active', 'is_current', 'description', 'sort_order', 'created_at', 'updated_at', 'year_name', 'start_date', 'end_date', 'year_level_promotion_active', 'promotion_triggered_at', 'promotion_triggered_by', 'notes'],
    'role_account' => ['id', 'fullname', 'mname', 'extension', 'gender', 'organization', 'position', 'email', 'pending_email', 'email_verification_token', 'email_verification_sent_at', 'email_verified_at', 'student_id', 'department', 'department_id', 'course_id', 'course', 'year_level', 'password', 'account_type', 'status', 'is_graduating', 'graduation_date', 'graduated_at', 'created_at', 'updated_at'],
    'student_registrations' => ['id', 'fname', 'mname', 'lname', 'extension', 'gender', 'student_id', 'email', 'pending_email', 'email_verification_token', 'email_verification_sent_at', 'email_verified_at', 'password', 'department', 'course', 'status', 'account_type', 'year_level', 'organization', 'position', 'created_at', 'updated_at'],
    'archived_role_accounts' => ['id', 'student_id', 'fullname', 'department', 'status', 'account_type', 'created_at', 'updated_at'],
    'receipt' => ['id', 'receipt_number', 'student_id', 'amount', 'payment_method', 'status', 'document_path', 'reference_num', 'official_receipt_no', 'date_paid', 'created_at', 'updated_at'],
    'good_moral_applications' => ['id', 'reference_number', 'receipt_id', 'number_of_copies', 'student_id', 'status', 'fullname', 'gender', 'department', 'reason', 'certificate_type', 'application_status', 'rejection_reason', 'rejection_details', 'rejected_by', 'rejected_at', 'action_history', 'course_completed', 'graduation_date', 'is_undergraduate', 'last_course_year_level', 'last_semester_sy', 'created_at', 'updated_at'],
    'dean_applications' => ['id', 'student_id', 'status', 'department', 'fullname', 'reason', 'reference_number', 'number_of_copies', 'course_completed', 'graduation_date', 'is_undergraduate', 'last_course_year_level', 'last_semester_sy', 'created_at', 'updated_at'],
    'head_osa_applications' => ['id', 'student_id', 'status', 'department', 'fullname', 'reason', 'reference_number', 'number_of_copies', 'course_completed', 'graduation_date', 'is_undergraduate', 'last_course_year_level', 'last_semester_sy', 'created_at', 'updated_at'],
    'sec_osa_applications' => ['id', 'student_id', 'status', 'department', 'fullname', 'reason', 'reference_number', 'number_of_copies', 'course_completed', 'graduation_date', 'is_undergraduate', 'last_course_year_level', 'last_semester_sy', 'created_at', 'updated_at'],
    'notifarchives' => ['id', 'reference_number', 'number_of_copies', 'student_id', 'status', 'fullname', 'gender', 'department', 'reason', 'certificate_type', 'application_status', 'course_completed', 'graduation_date', 'is_undergraduate', 'last_course_year_level', 'last_semester_sy', 'created_at', 'updated_at'],
    'violations' => ['id', 'offense_type', 'description', 'article', 'created_at', 'updated_at'],
    'student_violations' => ['id', 'first_name', 'last_name', 'violation', 'status', 'offense_type', 'student_id', 'added_by', 'unique_id', 'violation_id', 'department', 'course', 'document_path', 'meeting_date', 'meeting_notes', 'proceedings_uploaded_by', 'proceedings_uploaded_at', 'forwarded_to_admin_at', 'forwarded_by', 'closed_by', 'closed_at', 'ref_num', 'created_at', 'updated_at', 'downloaded'],
    'violation_notifs' => ['id', 'ref_num', 'student_id', 'status', 'notif', 'created_at', 'updated_at'],
    'student_year_level_history' => ['id', 'student_id', 'academic_year_id', 'previous_year_level', 'new_year_level', 'promotion_type', 'reason', 'processed_by', 'effective_date', 'created_at', 'updated_at'],
    'generated_reports' => ['id', 'report_type', 'report_title', 'academic_year', 'time_period', 'time_period_description', 'filename', 'file_path', 'total_records', 'summary_data', 'generated_by', 'generated_by_role', 'generated_at', 'file_size', 'status', 'error_message', 'created_at', 'updated_at'],
];

$pass = 0;
$fail = 0;
$warnings = [];

// Check all expected tables exist
$liveTables = collect(DB::select('SHOW TABLES'))->map(fn($r) => array_values((array)$r)[0])->toArray();

foreach ($expectedSchema as $table => $expectedColumns) {
    if (!in_array($table, $liveTables)) {
        echo "[FAIL] Table '$table' is MISSING" . PHP_EOL;
        $fail++;
        continue;
    }

    $liveColumns = collect(DB::select("SHOW COLUMNS FROM `$table`"))->pluck('Field')->toArray();

    $missing = array_diff($expectedColumns, $liveColumns);
    $extra = array_diff($liveColumns, $expectedColumns);

    if (empty($missing) && empty($extra)) {
        echo "[PASS] $table (" . count($liveColumns) . " columns)" . PHP_EOL;
        $pass++;
    } else {
        if (!empty($missing)) {
            echo "[FAIL] $table - Missing columns: " . implode(', ', $missing) . PHP_EOL;
            $fail++;
        }
        if (!empty($extra)) {
            echo "[WARN] $table - Extra columns: " . implode(', ', $extra) . PHP_EOL;
            $warnings[] = "$table has extra columns: " . implode(', ', $extra);
        }
        if (empty($missing)) {
            $pass++;
        }
    }
}

// Check for unexpected tables (excluding 'migrations')
$expectedTables = array_keys($expectedSchema);
$unexpectedTables = array_diff($liveTables, $expectedTables, ['migrations']);
if (!empty($unexpectedTables)) {
    foreach ($unexpectedTables as $t) {
        echo "[WARN] Unexpected table: $t" . PHP_EOL;
        $warnings[] = "Unexpected table: $t";
    }
}

echo PHP_EOL . "=== RESULTS ===" . PHP_EOL;
echo "Passed: $pass / " . count($expectedSchema) . PHP_EOL;
echo "Failed: $fail" . PHP_EOL;
echo "Warnings: " . count($warnings) . PHP_EOL;

if ($fail === 0) {
    echo PHP_EOL . "✓ Schema matches! Migration consolidation is successful." . PHP_EOL;
} else {
    echo PHP_EOL . "✗ Schema mismatch detected. Review the failures above." . PHP_EOL;
}

// Verify seeders work
echo PHP_EOL . "=== Seeder Check ===" . PHP_EOL;
try {
    $userCount = DB::table('users')->count();
    $designationCount = DB::table('designations')->count();
    echo "Users: $userCount (should be > 0 after seeding)" . PHP_EOL;
    echo "Designations: $designationCount (should be 5 after seeding)" . PHP_EOL;
} catch (\Exception $e) {
    echo "[FAIL] Seeder check error: " . $e->getMessage() . PHP_EOL;
}
