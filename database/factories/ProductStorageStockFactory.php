<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ProductStorageStock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductStorageStockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductStorageStock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'storage_stock_id' => \App\Models\StorageStock::factory(),
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
