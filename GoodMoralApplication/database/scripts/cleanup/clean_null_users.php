<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== Cleaning Users Table ===\n\n";

// Count records with NULL student_id
$nullRecords = User::whereNull('student_id')
    ->orWhereNull('gender')
    ->orWhereNull('department')
    ->orWhereNull('year_level')
    ->get();

echo "Found " . $nullRecords->count() . " records with NULL values in essential fields\n\n";

if ($nullRecords->count() > 0) {
    echo "Records to be deleted:\n";
    echo str_repeat("-", 80) . "\n";
    printf("%-5s %-30s %-20s %-15s\n", "ID", "Name", "Email", "Role");
    echo str_repeat("-", 80) . "\n";
    
    foreach ($nullRecords as $record) {
        printf("%-5s %-30s %-20s %-15s\n", 
            $record->id, 
            $record->name ?? 'NULL',
            $record->email ?? 'NULL',
            $record->role ?? 'NULL'
        );
    }
    echo str_repeat("-", 80) . "\n\n";
    
    // Delete the records
    $deleted = User::whereNull('student_id')
        ->orWhereNull('gender')
        ->orWhereNull('department')
        ->orWhereNull('year_level')
        ->delete();
    
    echo "✓ Deleted $deleted records with NULL values\n";
} else {
    echo "✓ No records with NULL values found\n";
}

// Show remaining records
$remaining = User::count();
echo "\nRemaining user records: $remaining\n";

echo "\n=== Cleanup Complete ===\n";
