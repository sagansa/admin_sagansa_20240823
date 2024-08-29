<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ClosingCourier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClosingCourierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClosingCourier::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total_cash_to_transfer' => fake()->randomNumber(),
            'status' => fake()->word(),
            'notes' => fake()->text(),
            'bank_id' => \App\Models\Bank::factory(),
            'approved_by_id' => \App\Models\User::factory(),
            'approved_by_id' => \App\Models\User::factory(),
        ];
    }
}
