<?php

namespace Database\Seeders;

use App\Models\FuelService;
use Illuminate\Database\Seeder;

class FuelServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FuelService::factory()
            ->count(5)
            ->create();
    }
}
