<?php

namespace Database\Seeders;

use App\Models\RemainingStore;
use Illuminate\Database\Seeder;

class RemainingStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RemainingStore::factory()
            ->count(5)
            ->create();
    }
}
