<?php

namespace Database\Seeders;

use App\Models\Hygiene;
use Illuminate\Database\Seeder;

class HygieneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hygiene::factory()
            ->count(5)
            ->create();
    }
}
