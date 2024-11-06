<?php

namespace Database\Seeders;

use App\Models\SalaryRate;
use Illuminate\Database\Seeder;

class SalaryRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SalaryRate::factory()
            ->count(5)
            ->create();
    }
}
