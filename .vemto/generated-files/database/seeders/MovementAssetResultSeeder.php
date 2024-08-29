<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MovementAssetResult;

class MovementAssetResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MovementAssetResult::factory()
            ->count(5)
            ->create();
    }
}
