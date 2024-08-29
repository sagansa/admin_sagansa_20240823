<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ContractLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractLocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractLocation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from_date' => fake()->date(),
            'until_date' => fake()->date(),
            'nominal_contract' => fake()->randomNumber(),
            'location_id' => \App\Models\Location::factory(),
        ];
    }
}
