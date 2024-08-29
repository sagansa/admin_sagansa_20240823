<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryService;

class DeliveryServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeliveryService::factory()
            ->count(5)
            ->create();
    }
}
