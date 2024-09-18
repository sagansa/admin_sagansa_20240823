<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductStorageStock;

class ProductStorageStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductStorageStock::factory()
            ->count(5)
            ->create();
    }
}
