<?php

namespace Database\Seeders;

use App\Models\ShiftStore;
use Illuminate\Database\Seeder;

class ShiftStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShiftStore::factory()
            ->count(5)
            ->create();
    }
}
