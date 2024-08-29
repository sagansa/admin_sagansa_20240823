<?php

namespace Database\Seeders;

use App\Models\DetailInvoice;
use Illuminate\Database\Seeder;

class DetailInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailInvoice::factory()
            ->count(5)
            ->create();
    }
}
