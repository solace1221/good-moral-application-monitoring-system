<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organizations = [
            'PSG Uniwide',
            'PSG SASTE',
            'PSG SBAHM',
            'PSG SITE',
            'PSG SNAHS',

        ];

        foreach ($organizations as $description) {
            Organization::firstOrCreate(
                ['description' => $description],
            );
        }
    }
}
