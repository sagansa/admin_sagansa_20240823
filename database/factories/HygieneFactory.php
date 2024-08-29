<?php

namespace Database\Factories;

use App\Models\Hygiene;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class HygieneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Hygiene::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->word(),
            'notes' => fake()->text(),
            'store_id' => \App\Models\Store::factory(),
            'approved_by_id' => \App\Models\User::factory(),
            'approved_by_id' => \App\Models\User::factory(),
        ];
    }
}
