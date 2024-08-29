<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailAdvancePurchase;

class DetailAdvancePurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailAdvancePurchase::factory()
            ->count(5)
            ->create();
    }
}
