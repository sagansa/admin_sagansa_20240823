<?php

namespace Database\Seeders;

use App\Models\MaterialGroup;
use Illuminate\Database\Seeder;

class MaterialGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MaterialGroup::factory()
            ->count(5)
            ->create();
    }
}
