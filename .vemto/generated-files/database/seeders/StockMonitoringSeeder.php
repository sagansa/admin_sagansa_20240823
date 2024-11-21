<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockMonitoring;

class StockMonitoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockMonitoring::factory()
            ->count(5)
            ->create();
    }
}
