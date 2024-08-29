<?php

namespace Database\Seeders;

use App\Models\TransferStock;
use Illuminate\Database\Seeder;

class TransferStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransferStock::factory()
            ->count(5)
            ->create();
    }
}
