<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvoicePurchase;

class InvoicePurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InvoicePurchase::factory()
            ->count(5)
            ->create();
    }
}
