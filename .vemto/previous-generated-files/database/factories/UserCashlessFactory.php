<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\UserCashless;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCashlessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserCashless::class;

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
            'username' => fake()->word(),
            'password' => \Hash::make('password'),
            'no_telp' => fake()->word(),
            'cashless_provider_id' => \App\Models\CashlessProvider::factory(),
            'store_id' => \App\Models\Store::factory(),
            'store_cashless_id' => \App\Models\StoreCashless::factory(),
        ];
    }
}
