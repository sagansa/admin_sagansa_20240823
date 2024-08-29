<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SelfConsumption;

class SelfConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SelfConsumption::factory()
            ->count(5)
            ->create();
    }
}
