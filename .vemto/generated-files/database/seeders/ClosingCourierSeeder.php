<?php

namespace Database\Seeders;

use App\Models\ClosingCourier;
use Illuminate\Database\Seeder;

class ClosingCourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClosingCourier::factory()
            ->count(5)
            ->create();
    }
}
