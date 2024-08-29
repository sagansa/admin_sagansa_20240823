<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ProductRemainingStock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductRemainingStockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductRemainingStock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomNumber(),
            'remaining_stock_id' => \App\Models\RemainingStock::factory(),
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
