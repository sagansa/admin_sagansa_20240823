<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContractLocation;

class ContractLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContractLocation::factory()
            ->count(5)
            ->create();
    }
}
