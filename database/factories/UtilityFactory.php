<?php

namespace Database\Factories;

use App\Models\Utility;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UtilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Utility::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => fake()->randomNumber(),
            'name' => fake()->name(),
            'category' => fake()->numberBetween(0, 127),
            'pre_post' => fake()->numberBetween(0, 127),
            'status' => fake()->word(),
            'store_id' => \App\Models\Store::factory(),
            'unit_id' => \App\Models\Unit::factory(),
            'utility_provider_id' => \App\Models\UtilityProvider::factory(),
        ];
    }
}
