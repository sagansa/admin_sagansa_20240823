<?php

namespace Database\Seeders;

use App\Models\TransferCard;
use Illuminate\Database\Seeder;

class TransferCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransferCard::factory()
            ->count(5)
            ->create();
    }
}
