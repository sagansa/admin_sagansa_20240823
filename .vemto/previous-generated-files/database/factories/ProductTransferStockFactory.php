<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ProductTransferStock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductTransferStockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductTransferStock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomNumber(),
            'transfer_stock_id' => \App\Models\TransferStock::factory(),
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
