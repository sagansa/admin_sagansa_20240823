<?php

namespace Database\Seeders;

use App\Models\MonthlySalary;
use Illuminate\Database\Seeder;

class MonthlySalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MonthlySalary::factory()
            ->count(5)
            ->create();
    }
}
