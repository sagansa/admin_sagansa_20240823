<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockMonitoringDetail;

class StockMonitoringDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockMonitoringDetail::factory()
            ->count(5)
            ->create();
    }
}
