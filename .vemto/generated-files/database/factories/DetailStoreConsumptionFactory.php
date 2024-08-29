<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\DetailStoreConsumption;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailStoreConsumptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DetailStoreConsumption::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomNumber(),
            'product_consumption_id' => \App\Models\ProductConsumption::factory(),
        ];
    }
}
