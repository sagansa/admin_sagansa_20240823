<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OnlineShopProvider;

class OnlineShopProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OnlineShopProvider::factory()
            ->count(5)
            ->create();
    }
}
