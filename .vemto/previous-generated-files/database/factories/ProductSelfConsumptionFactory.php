<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ProductSelfConsumption;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductSelfConsumptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductSelfConsumption::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomNumber(),
            'self_consumption_id' => \App\Models\SelfConsumption::factory(),
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
