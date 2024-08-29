<?php

namespace Database\Seeders;

use App\Models\OnlineCategory;
use Illuminate\Database\Seeder;

class OnlineCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OnlineCategory::factory()
            ->count(5)
            ->create();
    }
}
