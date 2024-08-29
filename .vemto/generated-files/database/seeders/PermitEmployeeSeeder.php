<?php

namespace Database\Seeders;

use App\Models\PermitEmployee;
use Illuminate\Database\Seeder;

class PermitEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PermitEmployee::factory()
            ->count(5)
            ->create();
    }
}
