<?php

namespace Database\Factories;

use App\Models\UtilityBill;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UtilityBillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UtilityBill::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => fake()->word(),
            'date' => fake()->date(),
            'amount' => fake()->randomNumber(),
            'initial_indicator' => fake()->randomNumber(),
            'last_indicator' => fake()->randomNumber(),
            'utility_id' => \App\Models\Utility::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
