<?php

namespace Database\Seeders;

use App\Models\RemainingStock;
use Illuminate\Database\Seeder;

class RemainingStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RemainingStock::factory()
            ->count(5)
            ->create();
    }
}
