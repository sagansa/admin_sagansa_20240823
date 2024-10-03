<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransferCardStorage;

class TransferCardStorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransferCardStorage::factory()
            ->count(5)
            ->create();
    }
}
