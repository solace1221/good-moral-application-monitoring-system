<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    echo "Checking users table structure:\n";
    echo "================================\n\n";

    // Get column names
    $columns = Schema::getColumnListing('users');
    echo "Columns in users table:\n";
    print_r($columns);
    echo "\n";

    // Get a sample user
    $user = DB::table('users')->first();
    if ($user) {
        echo "Sample user data:\n";
        print_r($user);
    } else {
        echo "No users found in database\n";
    }

    echo "\n";

    // Check if specific columns exist
    $hasRole = Schema::hasColumn('users', 'role');
    $hasAccountType = Schema::hasColumn('users', 'account_type');
    $hasStatus = Schema::hasColumn('users', 'status');

    echo "Column existence check:\n";
    echo "- role: " . ($hasRole ? "YES" : "NO") . "\n";
    echo "- account_type: " . ($hasAccountType ? "YES" : "NO") . "\n";
    echo "- status: " . ($hasStatus ? "YES" : "NO") . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
