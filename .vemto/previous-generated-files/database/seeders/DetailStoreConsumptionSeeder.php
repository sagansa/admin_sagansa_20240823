<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailStoreConsumption;

class DetailStoreConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailStoreConsumption::factory()
            ->count(5)
            ->create();
    }
}
