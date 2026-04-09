<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        $designations = [
            'PSG Uniwide',
            'PSG SASTE',
            'PSG SBAHM',
            'PSG SITE',
            'PSG SNAHS',

        ];

        foreach ($designations as $description) {
            Designation::firstOrCreate(
                ['description' => $description],
            );
        }
    }
}
