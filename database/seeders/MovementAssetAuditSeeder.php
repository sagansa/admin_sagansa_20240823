<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MovementAssetAudit;

class MovementAssetAuditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MovementAssetAudit::factory()
            ->count(5)
            ->create();
    }
}
