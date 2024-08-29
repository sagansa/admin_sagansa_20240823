<?php

namespace Database\Seeders;

use App\Models\ProductionTo;
use Illuminate\Database\Seeder;

class ProductionToSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductionTo::factory()
            ->count(5)
            ->create();
    }
}
