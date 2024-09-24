<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSelfConsumption;

class ProductSelfConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductSelfConsumption::factory()
            ->count(5)
            ->create();
    }
}
