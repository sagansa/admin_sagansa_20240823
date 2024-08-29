<?php

namespace Database\Seeders;

use App\Models\Production;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Production::factory()
            ->count(5)
            ->create();
    }
}
