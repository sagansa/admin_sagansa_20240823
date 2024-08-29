<?php

namespace Database\Seeders;

use App\Models\VehicleTax;
use Illuminate\Database\Seeder;

class VehicleTaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleTax::factory()
            ->count(5)
            ->create();
    }
}
