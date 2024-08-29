<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\DetailSalesOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailSalesOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DetailSalesOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomNumber(),
            'unit_price' => fake()->randomNumber(),
            'subtotal_price' => fake()->randomNumber(),
            'product_id' => \App\Models\Product::factory(),
            'sales_order_id' => \App\Models\SalesOrder::factory(),
            'sales_order_id' => \App\Models\SalesOrderOnline::factory(),
            'sales_order_id' => \App\Models\SalesOrderDirect::factory(),
            'sales_order_id' => \App\Models\SalesOrderEmployee::factory(),
        ];
    }
}
