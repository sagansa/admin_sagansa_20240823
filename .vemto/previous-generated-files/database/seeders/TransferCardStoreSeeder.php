<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransferCardStore;

class TransferCardStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransferCardStore::factory()
            ->count(5)
            ->create();
    }
}
