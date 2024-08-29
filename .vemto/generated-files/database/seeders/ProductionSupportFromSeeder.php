<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductionSupportFrom;

class ProductionSupportFromSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductionSupportFrom::factory()
            ->count(5)
            ->create();
    }
}
