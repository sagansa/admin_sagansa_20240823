<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailStockCard;

class DetailStockCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailStockCard::factory()
            ->count(5)
            ->create();
    }
}
