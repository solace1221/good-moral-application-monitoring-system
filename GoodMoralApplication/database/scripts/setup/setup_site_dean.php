<?php
/**
 * Create or update SITE dean account for testing
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

echo "=== CREATING/UPDATING SITE DEAN ACCOUNT ===\n\n";

// Get SITE department
$siteDept = Department::where('department_code', 'SITE')->first();
if (!$siteDept) {
    echo "ERROR: SITE department not found in departments table!\n";
    exit(1);
}

echo "Found SITE department (ID: {$siteDept->id})\n";

// Check if deanSITE account exists
$dean = User::where('email', 'deanSITE@admin.com')->first();

if ($dean) {
    echo "Found existing deanSITE account\n";
    echo "Current department_id: {$dean->department_id}\n";
    echo "Current role: {$dean->role}\n";
    
    // Update to ensure it's correct
    $dean->department_id = $siteDept->id;
    $dean->role = 'dean';
    $dean->save();
    echo "Updated department_id to: {$siteDept->id} (SITE)\n";
} else {
    echo "Creating new deanSITE account...\n";
    
    $dean = User::create([
        'email' => 'deanSITE@admin.com',
        'password' => Hash::make('password123'),
        'role' => 'dean',
        'department_id' => $siteDept->id,
        'name' => 'Dean of SITE',
        'firstname' => 'Dean',
        'lastname' => 'SITE',
        'email_verified_at' => now(),
    ]);
    
    echo "Created deanSITE account\n";
}

echo "\n=== DEAN ACCOUNT DETAILS ===\n";
echo "Email: {$dean->email}\n";
echo "Password: password123\n";
echo "Department ID: {$dean->department_id}\n";
echo "Department Code: {$dean->department}\n"; // Uses accessor
echo "Role: {$dean->role}\n";
echo "Account Type: {$dean->account_type}\n"; // Uses accessor

echo "\n=== VERIFYING VIOLATIONS ACCESS ===\n";
use App\Models\StudentViolation;

// Map department abbreviations to possible full names
$departmentMap = [
  'SITE' => ['SITE', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING'],
];

$possibleDepartments = $departmentMap['SITE'];

$minorCount = StudentViolation::whereIn('department', $possibleDepartments)
    ->where('offense_type', 'minor')
    ->count();

$majorCount = StudentViolation::whereIn('department', $possibleDepartments)
    ->where('offense_type', 'major')
    ->count();

echo "Minor violations accessible: $minorCount\n";
echo "Major violations accessible: $majorCount\n";

if ($minorCount > 0 || $majorCount > 0) {
    echo "\n✅ SUCCESS! Dean can now see violations!\n";
    echo "\nLogin credentials:\n";
    echo "Email: deanSITE@admin.com\n";
    echo "Password: password123\n";
} else {
    echo "\n⚠️ No violations found. You may need to add some test violations.\n";
}

echo "\n=== COMPLETE ===\n";
