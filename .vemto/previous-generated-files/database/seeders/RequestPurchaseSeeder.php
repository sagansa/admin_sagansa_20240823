<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RequestPurchase;

class RequestPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RequestPurchase::factory()
            ->count(5)
            ->create();
    }
}
