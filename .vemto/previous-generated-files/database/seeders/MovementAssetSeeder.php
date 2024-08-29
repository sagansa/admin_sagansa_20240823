<?php

namespace Database\Seeders;

use App\Models\MovementAsset;
use Illuminate\Database\Seeder;

class MovementAssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MovementAsset::factory()
            ->count(5)
            ->create();
    }
}
