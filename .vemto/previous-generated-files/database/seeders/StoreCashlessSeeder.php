<?php

namespace Database\Seeders;

use App\Models\StoreCashless;
use Illuminate\Database\Seeder;

class StoreCashlessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoreCashless::factory()
            ->count(5)
            ->create();
    }
}
