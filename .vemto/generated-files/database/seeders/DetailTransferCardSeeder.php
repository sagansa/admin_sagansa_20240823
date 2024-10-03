<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailTransferCard;

class DetailTransferCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailTransferCard::factory()
            ->count(5)
            ->create();
    }
}
