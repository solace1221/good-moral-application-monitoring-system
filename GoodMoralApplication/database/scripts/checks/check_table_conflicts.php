<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$existingTables = [];
$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $existingTables[] = array_values((array)$table)[0];
}

// Tables that Good Moral migrations expect to create
$expectedTables = [
    'users',
    'cache',
    'cache_locks', 
    'job_batches',
    'jobs',
    'failed_jobs',
    'academic_years',
    'courses'
];

echo "Table Conflict Analysis:\n";
echo str_repeat('=', 80) . "\n\n";

foreach ($expectedTables as $table) {
    $exists = in_array($table, $existingTables);
    $status = $exists ? '❌ EXISTS' : '✅ NEW';
    echo "$status - $table\n";
}

echo "\n\nTables in current database:\n";
echo str_repeat('-', 80) . "\n";
foreach ($existingTables as $table) {
    echo "- $table\n";
}
