<?php

namespace Database\Seeders;

use App\Models\Readiness;
use Illuminate\Database\Seeder;

class ReadinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Readiness::factory()
            ->count(5)
            ->create();
    }
}
