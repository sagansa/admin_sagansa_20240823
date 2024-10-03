<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreConsumption;

class StoreConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoreConsumption::factory()
            ->count(5)
            ->create();
    }
}
