<?php

namespace Database\Seeders;

use App\Models\AdminCashless;
use Illuminate\Database\Seeder;

class AdminCashlessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminCashless::factory()
            ->count(5)
            ->create();
    }
}
