<?php

namespace Database\Seeders;

use App\Models\HygieneOfRoom;
use Illuminate\Database\Seeder;

class HygieneOfRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HygieneOfRoom::factory()
            ->count(5)
            ->create();
    }
}
