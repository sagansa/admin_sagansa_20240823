<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FuelServicePaymentReceipt;

class FuelServicePaymentReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FuelServicePaymentReceipt::factory()
            ->count(5)
            ->create();
    }
}
