<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailSalesOrder;

class DetailSalesOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailSalesOrder::factory()
            ->count(5)
            ->create();
    }
}
