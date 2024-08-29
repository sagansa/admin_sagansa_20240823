<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\UtilityProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class UtilityProviderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UtilityProvider::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'category' => fake()->numberBetween(0, 127),
        ];
    }
}
