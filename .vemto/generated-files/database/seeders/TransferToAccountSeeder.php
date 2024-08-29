<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransferToAccount;

class TransferToAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransferToAccount::factory()
            ->count(5)
            ->create();
    }
}
