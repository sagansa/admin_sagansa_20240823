<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvoicePurchasePaymentReceipt;

class InvoicePurchasePaymentReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InvoicePurchasePaymentReceipt::factory()
            ->count(5)
            ->create();
    }
}
