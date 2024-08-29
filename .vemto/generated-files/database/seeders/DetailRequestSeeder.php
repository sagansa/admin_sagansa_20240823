<?php

namespace Database\Seeders;

use App\Models\DetailRequest;
use Illuminate\Database\Seeder;

class DetailRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailRequest::factory()
            ->count(5)
            ->create();
    }
}
