<?php
// Mark migrations as complete
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$db = DB::connection();

// Migration files
$migrations = [
    '2026_02_05_223806_create_designations_table',
    '2026_02_05_223846_create_positions_table'
];

foreach ($migrations as $migration) {
    // Check if already exists
    $exists = $db->table('migrations')->where('migration', $migration)->exists();
    
    if (!$exists) {
        $db->table('migrations')->insert([
            'migration' => $migration,
            'batch' => $db->table('migrations')->max('batch') + 1
        ]);
        echo "✓ Marked migration as complete: {$migration}\n";
    } else {
        echo "✓ Migration already marked as complete: {$migration}\n";
    }
}

echo "\nAll migrations marked as complete.\n";
