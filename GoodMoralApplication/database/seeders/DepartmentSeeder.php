<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = config('courses.departments');

        foreach ($departments as $code => $dept) {
            DB::table('departments')->updateOrInsert(
                ['department_code' => $code],
                [
                    'department_name' => $dept['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Seeded ' . count($departments) . ' departments.');
    }
}
