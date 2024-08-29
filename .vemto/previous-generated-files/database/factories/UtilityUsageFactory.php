<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\UtilityUsage;
use Illuminate\Database\Eloquent\Factories\Factory;

class UtilityUsageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UtilityUsage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'result' => fake()->randomNumber(1),
            'status' => fake()->word(),
            'notes' => fake()->text(),
            'utility_id' => \App\Models\Utility::factory(),
            'approved_by_id' => \App\Models\User::factory(),
            'approved_by_id' => \App\Models\User::factory(),
        ];
    }
}
