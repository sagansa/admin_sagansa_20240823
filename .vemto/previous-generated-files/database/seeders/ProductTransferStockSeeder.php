<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductTransferStock;

class ProductTransferStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductTransferStock::factory()
            ->count(5)
            ->create();
    }
}
