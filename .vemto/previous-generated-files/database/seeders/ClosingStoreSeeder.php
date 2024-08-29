<?php

namespace Database\Seeders;

use App\Models\ClosingStore;
use Illuminate\Database\Seeder;

class ClosingStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClosingStore::factory()
            ->count(5)
            ->create();
    }
}
