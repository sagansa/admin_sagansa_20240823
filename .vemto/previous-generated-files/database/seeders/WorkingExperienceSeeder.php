<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkingExperience;

class WorkingExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkingExperience::factory()
            ->count(5)
            ->create();
    }
}
