<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\StoreConsumption;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreConsumptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StoreConsumption::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'store_id' => \App\Models\Store::factory(),
        ];
    }
}
