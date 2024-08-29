<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\AccountCashless;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountCashlessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccountCashless::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()
                ->unique()
                ->safeEmail(),
            'username' => fake()->text(255),
            'password' => \Hash::make('password'),
            'no_telp' => fake()->text(255),
            'status' => fake()->word(),
            'notes' => fake()->text(),
            'cashless_provider_id' => \App\Models\CashlessProvider::factory(),
            'store_id' => \App\Models\Store::factory(),
            'store_cashless_id' => \App\Models\StoreCashless::factory(),
        ];
    }
}
