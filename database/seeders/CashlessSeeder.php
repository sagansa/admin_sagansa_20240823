<?php

namespace Database\Seeders;

use App\Models\Cashless;
use Illuminate\Database\Seeder;

class CashlessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cashless::factory()
            ->count(5)
            ->create();
    }
}
