<?php

namespace Database\Seeders;

use App\Models\DailySalary;
use Illuminate\Database\Seeder;

class DailySalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DailySalary::factory()
            ->count(5)
            ->create();
    }
}
