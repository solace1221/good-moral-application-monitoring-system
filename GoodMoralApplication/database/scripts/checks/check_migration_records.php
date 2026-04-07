<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$migrations = DB::table('migrations')->orderBy('id')->get();

echo "Recorded migrations in database:\n";
echo str_repeat('=', 80) . "\n";
foreach ($migrations as $migration) {
    echo "{$migration->id}. {$migration->migration} (batch {$migration->batch})\n";
}
