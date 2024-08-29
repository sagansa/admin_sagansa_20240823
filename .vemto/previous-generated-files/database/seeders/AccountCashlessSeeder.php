<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountCashless;

class AccountCashlessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AccountCashless::factory()
            ->count(5)
            ->create();
    }
}
