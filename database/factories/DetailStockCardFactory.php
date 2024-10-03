<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\DetailStockCard;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailStockCardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DetailStockCard::class;

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
            'stock_card_id' => \App\Models\StockCard::factory(),
        ];
    }
}
