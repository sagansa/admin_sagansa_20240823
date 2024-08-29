<?php

namespace Database\Factories;

use App\Models\Readiness;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReadinessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Readiness::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image_selfie' => fake()->text(255),
            'left_hand' => fake()->text(255),
            'right_hand' => fake()->text(255),
            'status' => fake()->word(),
            'notes' => fake()->text(),
            'approved_by_id' => \App\Models\User::factory(),
            'approved_by_id' => \App\Models\User::factory(),
        ];
    }
}
