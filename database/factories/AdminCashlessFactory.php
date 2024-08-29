<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\AdminCashless;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminCashlessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdminCashless::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->text(255),
            'email' => fake()
                ->unique()
                ->safeEmail(),
            'no_telp' => fake()->text(255),
            'password' => \Hash::make('password'),
            'cashless_provider_id' => \App\Models\CashlessProvider::factory(),
        ];
    }
}
