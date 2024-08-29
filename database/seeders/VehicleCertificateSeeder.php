<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleCertificate;

class VehicleCertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleCertificate::factory()
            ->count(5)
            ->create();
    }
}
