<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeConsumption;

class EmployeeConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeConsumption::factory()
            ->count(5)
            ->create();
    }
}
