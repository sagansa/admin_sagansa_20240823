<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\PermitEmployee;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermitEmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PermitEmployee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reason' => fake()->numberBetween(0, 127),
            'from_date' => fake()->date(),
            'until_date' => fake()->date(),
            'status' => fake()->word(),
            'notes' => fake()->text(),
            'approved_by_id' => \App\Models\User::factory(),
            'approved_by_id' => \App\Models\User::factory(),
        ];
    }
}
