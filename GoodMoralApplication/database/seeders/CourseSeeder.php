<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $departments = config('courses.departments');
        $count = 0;
        $sortOrder = 0;

        foreach ($departments as $deptCode => $dept) {
            foreach ($dept['courses'] as $courseCode => $courseName) {
                DB::table('courses')->updateOrInsert(
                    ['course_code' => $courseCode],
                    [
                        'course_name' => $courseName,
                        'department' => $deptCode,
                        'department_name' => $dept['name'],
                        'sort_order' => $sortOrder++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                $count++;
            }
        }

        $this->command->info("Seeded {$count} courses across " . count($departments) . " departments.");
    }
}
