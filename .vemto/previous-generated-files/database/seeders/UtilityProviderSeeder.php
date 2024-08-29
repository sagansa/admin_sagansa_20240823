<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UtilityProvider;

class UtilityProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UtilityProvider::factory()
            ->count(5)
            ->create();
    }
}
