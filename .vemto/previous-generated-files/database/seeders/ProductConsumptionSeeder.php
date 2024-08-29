<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductConsumption;

class ProductConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductConsumption::factory()
            ->count(5)
            ->create();
    }
}
