<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RemainingStorage;

class RemainingStorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RemainingStorage::factory()
            ->count(5)
            ->create();
    }
}
