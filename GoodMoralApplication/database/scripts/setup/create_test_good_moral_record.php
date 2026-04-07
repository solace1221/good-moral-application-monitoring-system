<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GoodMoralApplication;
use App\Models\RoleAccount;
use App\Models\Receipt;

echo "🔍 Creating test Good Moral Application record...\n\n";

try {
    // First, check if we have any students in the database
    $student = RoleAccount::where('account_type', 'student')->first();
    
    if (!$student) {
        echo "❌ No student found in the database. Creating a test student...\n";
        
        // Create a test student if none exists
        $student = RoleAccount::create([
            'student_id' => 'TEST-2024-001',
            'account_type' => 'student',
            'first_name' => 'John',
            'middle_name' => 'Michael',
            'last_name' => 'Doe',
            'suffix' => null,
            'fullname' => 'John Michael Doe',
            'email' => 'john.doe.test@example.com',
            'department' => 'College of Computer Studies',
            'year_level' => '4th Year',
            'program' => 'Bachelor of Science in Computer Science',
            'password' => bcrypt('password123'),
            'status' => 1,
        ]);
        
        echo "✅ Test student created: {$student->student_id}\n";
    } else {
        echo "✅ Using existing student: {$student->student_id}\n";
    }
    
    // Generate a unique reference number
    $referenceNumber = 'GM-' . date('Ymd') . '-' . strtoupper(substr(md5(microtime()), 0, 6));
    
    // Create the Good Moral Application
    $application = GoodMoralApplication::create([
        'fullname' => $student->fullname ?? trim("{$student->first_name} {$student->middle_name} {$student->last_name}"),
        'gender' => 'Male',
        'student_id' => $student->student_id,
        'reference_number' => $referenceNumber,
        'number_of_copies' => '2',
        'status' => 'approved',  // Set as approved so it appears in reports
        'department' => $student->department ?? 'College of Computer Studies',
        'reason' => json_encode(['Employment', 'Further Studies']),
        'course_completed' => $student->program ?? 'Bachelor of Science in Computer Science',
        'graduation_date' => now()->subMonths(6),
        'application_status' => 'completed',
        'is_undergraduate' => false,
        'last_course_year_level' => '4th Year',
        'last_semester_sy' => '2nd Semester 2023-2024',
        'certificate_type' => 'good_moral', // Enum value: 'good_moral' or 'residency'
    ]);
    
    echo "\n✅ Good Moral Application created successfully!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "ID: {$application->id}\n";
    echo "Reference Number: {$application->reference_number}\n";
    echo "Student ID: {$application->student_id}\n";
    echo "Full Name: {$application->fullname}\n";
    echo "Gender: {$application->gender}\n";
    echo "Department: {$application->department}\n";
    echo "Status: {$application->status}\n";
    echo "Application Status: {$application->application_status}\n";
    echo "Number of Copies: {$application->number_of_copies}\n";
    echo "Course Completed: {$application->course_completed}\n";
    echo "Graduation Date: {$application->graduation_date->format('Y-m-d')}\n";
    echo "Is Undergraduate: " . ($application->is_undergraduate ? 'Yes' : 'No') . "\n";
    echo "Certificate Type: {$application->certificate_type}\n";
    echo "Created At: {$application->created_at}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Create a dummy receipt for this application
    $receipt = Receipt::create([
        'reference_num' => $referenceNumber,
        'receipt_number' => 'OR-' . date('Ymd') . '-' . rand(1000, 9999),
        'amount' => 50.00,
        'payment_date' => now(),
        'status' => 'validated',
        'student_id' => $student->student_id,
    ]);
    
    echo "\n✅ Receipt created for the application!\n";
    echo "Receipt Number: {$receipt->receipt_number}\n";
    echo "Amount: ₱{$receipt->amount}\n";
    echo "Status: {$receipt->status}\n";
    
    echo "\n✨ Test record creation completed!\n";
    echo "📊 You can now check the Good Moral Applicant Reports.\n";
    echo "🔗 Navigate to: Admin Dashboard → Reports → Good Moral Applicants\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error creating test record:\n";
    echo $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
