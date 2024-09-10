<?php

namespace Database\Seeders;

use App\Models\StorageStock;
use Illuminate\Database\Seeder;

class StorageStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StorageStock::factory()
            ->count(5)
            ->create();
    }
}
