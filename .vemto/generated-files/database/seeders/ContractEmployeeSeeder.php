<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContractEmployee;

class ContractEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContractEmployee::factory()
            ->count(5)
            ->create();
    }
}
