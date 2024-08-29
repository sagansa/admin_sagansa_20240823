<?php

namespace Database\Seeders;

use App\Models\StoreAsset;
use Illuminate\Database\Seeder;

class StoreAssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoreAsset::factory()
            ->count(5)
            ->create();
    }
}
