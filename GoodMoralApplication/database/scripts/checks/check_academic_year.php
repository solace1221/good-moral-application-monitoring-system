<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$ay = DB::table('academic_years')->where('status', 'active')->first();
if ($ay) {
    echo "Active AY: {$ay->id} - {$ay->academic_year} ({$ay->semester})\n";
} else {
    echo "No active academic year found\n";
    $count = DB::table('academic_years')->count();
    echo "Total academic years: {$count}\n";

    if ($count > 0) {
        $latest = DB::table('academic_years')->latest('id')->first();
        echo "Latest: {$latest->id} - {$latest->academic_year} ({$latest->semester}) - Status: {$latest->status}\n";

        // Set it as active for testing
        echo "\nSetting latest academic year as active for testing...\n";
        DB::table('academic_years')->where('id', $latest->id)->update(['status' => 'active']);
        echo "Done! Academic year {$latest->id} is now active.\n";
    } else {
        echo "\nNo academic years exist. Creating one for testing...\n";
        $id = DB::table('academic_years')->insertGetId([
            'academic_year' => '2024-2025',
            'semester' => '1st Semester',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "Created academic year {$id} (2024-2025 - 1st Semester)\n";
    }
}
