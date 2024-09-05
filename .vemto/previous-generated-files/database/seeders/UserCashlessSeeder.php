<?php

namespace Database\Seeders;

use App\Models\UserCashless;
use Illuminate\Database\Seeder;

class UserCashlessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserCashless::factory()
            ->count(5)
            ->create();
    }
}
