<?php

namespace Database\Seeders;

use App\Models\UtilityBill;
use Illuminate\Database\Seeder;

class UtilityBillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UtilityBill::factory()
            ->count(5)
            ->create();
    }
}
