<?php

namespace Database\Factories;

use App\Models\CashAdvance;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashAdvanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashAdvance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'transfer' => fake()->randomNumber(),
            'before' => fake()->randomNumber(),
            'purchase' => fake()->randomNumber(),
            'remains' => fake()->randomNumber(),
            'status' => fake()->word(),
            'notes' => fake()->text(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
