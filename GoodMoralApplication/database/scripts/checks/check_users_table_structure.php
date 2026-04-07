<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Users Table Structure ===\n\n";

$columns = DB::select("DESCRIBE users");

echo "Current columns in users table:\n";
echo str_repeat("-", 80) . "\n";
printf("%-25s %-20s %-10s %-10s\n", "Field", "Type", "Null", "Key");
echo str_repeat("-", 80) . "\n";

foreach ($columns as $column) {
    printf("%-25s %-20s %-10s %-10s\n", 
        $column->Field, 
        $column->Type, 
        $column->Null, 
        $column->Key
    );
}

echo str_repeat("-", 80) . "\n";
echo "\nTotal columns: " . count($columns) . "\n\n";

// Check for name fields
$nameFields = ['fname', 'lname', 'mname', 'fullname', 'extension'];
$foundNameFields = [];

foreach ($nameFields as $field) {
    if (Schema::hasColumn('users', $field)) {
        $foundNameFields[] = $field;
    }
}

if (!empty($foundNameFields)) {
    echo "✓ Name fields present: " . implode(', ', $foundNameFields) . "\n";
} else {
    echo "✗ No name fields found\n";
}

// Check for required fields
$requiredFields = ['id', 'name', 'email', 'password', 'student_id', 'gender', 'department', 'year_level', 'role', 'status'];
$missingFields = [];

foreach ($requiredFields as $field) {
    if (!Schema::hasColumn('users', $field)) {
        $missingFields[] = $field;
    }
}

if (empty($missingFields)) {
    echo "✓ All required fields present!\n";
} else {
    echo "✗ Missing required fields: " . implode(', ', $missingFields) . "\n";
}

echo "\n=== Verification Complete ===\n";
