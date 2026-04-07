<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GoodMoralApplication;
use App\Models\RoleAccount;
use App\Models\Receipt;

echo "🔍 Creating multiple test Good Moral Application records for different academic years...\n\n";

try {
    // Get existing students
    $students = RoleAccount::where('account_type', 'student')->get();
    
    if ($students->isEmpty()) {
        echo "❌ No students found in the database.\n";
        exit;
    }
    
    // Academic years and semesters to test
    $academicYears = [
        [
            'semester' => '1st Semester 2023-2024',
            'grad_date' => '2024-03-15',
            'created_at' => '2024-03-20',
        ],
        [
            'semester' => '2nd Semester 2023-2024',
            'grad_date' => '2024-07-20',
            'created_at' => '2024-07-25',
        ],
        [
            'semester' => '1st Semester 2024-2025',
            'grad_date' => '2024-11-15',
            'created_at' => '2024-11-20',
        ],
        [
            'semester' => '2nd Semester 2024-2025',
            'grad_date' => '2025-05-20',
            'created_at' => '2025-05-25',
        ],
        [
            'semester' => '1st Semester 2025-2026',
            'grad_date' => '2025-11-15',
            'created_at' => '2025-11-20',
        ],
    ];
    
    $departments = ['SITE', 'SASTE', 'SBAHM', 'SNAHS', 'SOM'];
    $programs = [
        'Bachelor of Science in Computer Science',
        'Bachelor of Science in Information Technology',
        'Bachelor of Science in Nursing',
        'Bachelor of Science in Business Administration',
        'Bachelor of Elementary Education'
    ];
    $genders = ['Male', 'Female'];
    $certificateTypes = ['good_moral', 'residency'];
    
    $createdCount = 0;
    
    // Create 3 records for each academic year
    foreach ($academicYears as $index => $ayData) {
        for ($i = 0; $i < 3; $i++) {
            $student = $students->random();
            $referenceNumber = 'GM-' . date('Ymd', strtotime($ayData['created_at'])) . '-' . strtoupper(substr(md5(microtime() . $i), 0, 6));
            
            $application = GoodMoralApplication::create([
                'fullname' => $student->fullname ?? "Test Student " . ($createdCount + 1),
                'gender' => $genders[array_rand($genders)],
                'student_id' => $student->student_id,
                'reference_number' => $referenceNumber,
                'number_of_copies' => rand(1, 3),
                'status' => 'approved',
                'department' => $departments[array_rand($departments)],
                'reason' => json_encode(['Employment', 'Further Studies']),
                'course_completed' => $programs[array_rand($programs)],
                'graduation_date' => $ayData['grad_date'],
                'application_status' => 'completed',
                'is_undergraduate' => (bool)rand(0, 1),
                'last_course_year_level' => '4th Year',
                'last_semester_sy' => $ayData['semester'],
                'certificate_type' => $certificateTypes[array_rand($certificateTypes)],
                'created_at' => $ayData['created_at'],
                'updated_at' => $ayData['created_at'],
            ]);
            
            // Create receipt
            Receipt::create([
                'reference_num' => $referenceNumber,
                'receipt_number' => 'OR-' . date('Ymd', strtotime($ayData['created_at'])) . '-' . rand(1000, 9999),
                'amount' => rand(30, 100) + (rand(0, 99) / 100),
                'payment_date' => $ayData['created_at'],
                'status' => 'validated',
                'student_id' => $student->student_id,
                'created_at' => $ayData['created_at'],
                'updated_at' => $ayData['created_at'],
            ]);
            
            $createdCount++;
            
            echo "✅ Created record {$createdCount}: {$referenceNumber} - {$ayData['semester']}\n";
        }
    }
    
    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✨ Successfully created {$createdCount} test records!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "📊 Distribution:\n";
    foreach ($academicYears as $ay) {
        $count = GoodMoralApplication::where('last_semester_sy', $ay['semester'])->count();
        echo "   • {$ay['semester']}: {$count} records\n";
    }
    
    echo "\n🔗 You can now check the Good Moral Certificate report!\n";
    echo "   Navigate to: Admin Dashboard → Reports → Good Moral Certificate\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error creating test records:\n";
    echo $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
