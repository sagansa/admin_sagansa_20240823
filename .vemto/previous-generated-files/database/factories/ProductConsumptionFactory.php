<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ProductConsumption;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductConsumptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductConsumption::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->word(),
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
