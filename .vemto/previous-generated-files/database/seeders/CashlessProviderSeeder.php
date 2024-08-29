<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CashlessProvider;

class CashlessProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CashlessProvider::factory()
            ->count(5)
            ->create();
    }
}
