<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\SelfConsumption;
use Illuminate\Database\Eloquent\Factories\Factory;

class SelfConsumptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SelfConsumption::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'status' => fake()->word(),
            'notes' => fake()->text(),
            'store_id' => \App\Models\Store::factory(),
            'approved_by_id' => \App\Models\User::factory(),
            'approved_by_id' => \App\Models\User::factory(),
        ];
    }
}
