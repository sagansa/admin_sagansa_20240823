<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductionMainFrom;

class ProductionMainFromSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductionMainFrom::factory()
            ->count(5)
            ->create();
    }
}
