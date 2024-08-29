<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailySalaryPaymentReceipt;

class DailySalaryPaymentReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DailySalaryPaymentReceipt::factory()
            ->count(5)
            ->create();
    }
}
