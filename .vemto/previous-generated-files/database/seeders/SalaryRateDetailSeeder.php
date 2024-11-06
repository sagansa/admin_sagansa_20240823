<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalaryRateDetail;

class SalaryRateDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SalaryRateDetail::factory()
            ->count(5)
            ->create();
    }
}
