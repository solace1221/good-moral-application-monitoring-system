<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoricalViolationTrendsSeeder extends Seeder
{
    public function run(): void
    {
        // Map department codes to their IDs from the departments table
        $departments = DB::table('departments')->pluck('id', 'department_code');

        $records = [
            // AY 2023-2024
            ['department_code' => 'SITE',  'academic_year' => '2023-2024', 'minor_count' => 118, 'major_count' => 9,  'population' => 640],
            ['department_code' => 'SBAHM', 'academic_year' => '2023-2024', 'minor_count' => 88,  'major_count' => 15, 'population' => 727],
            ['department_code' => 'SNAHS', 'academic_year' => '2023-2024', 'minor_count' => 524, 'major_count' => 79, 'population' => 2831],
            ['department_code' => 'SASTE', 'academic_year' => '2023-2024', 'minor_count' => 97,  'major_count' => 4,  'population' => 409],

            // AY 2024-2025
            ['department_code' => 'SITE',  'academic_year' => '2024-2025', 'minor_count' => 90,  'major_count' => 6,  'population' => 640],
            ['department_code' => 'SBAHM', 'academic_year' => '2024-2025', 'minor_count' => 75,  'major_count' => 10, 'population' => 727],
            ['department_code' => 'SNAHS', 'academic_year' => '2024-2025', 'minor_count' => 420, 'major_count' => 60, 'population' => 2831],
            ['department_code' => 'SASTE', 'academic_year' => '2024-2025', 'minor_count' => 70,  'major_count' => 3,  'population' => 409],
        ];

        foreach ($records as $record) {
            $deptId = $departments[$record['department_code']] ?? null;

            if (!$deptId) {
                $this->command->warn("Department {$record['department_code']} not found, skipping.");
                continue;
            }

            DB::table('historical_violation_trends')->updateOrInsert(
                [
                    'department_id' => $deptId,
                    'academic_year' => $record['academic_year'],
                ],
                [
                    'minor_count' => $record['minor_count'],
                    'major_count' => $record['major_count'],
                    'population' => $record['population'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Seeded ' . count($records) . ' historical violation trend records.');
    }
}
