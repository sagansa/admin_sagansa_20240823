<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductRemainingStock;

class ProductRemainingStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductRemainingStock::factory()
            ->count(5)
            ->create();
    }
}
