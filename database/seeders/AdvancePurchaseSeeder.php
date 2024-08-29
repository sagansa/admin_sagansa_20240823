<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdvancePurchase;

class AdvancePurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdvancePurchase::factory()
            ->count(5)
            ->create();
    }
}
