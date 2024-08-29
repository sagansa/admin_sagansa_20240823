<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ProductionSupportFrom;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionSupportFromFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductionSupportFrom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomNumber(),
            'product_id' => \App\Models\Product::factory(),
            'production_id' => \App\Models\Production::factory(),
        ];
    }
}
