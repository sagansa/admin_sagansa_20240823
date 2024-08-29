<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\DetailAdvancePurchase;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailAdvancePurchaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DetailAdvancePurchase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomNumber(),
            'price' => fake()->randomFloat(2, 0, 9999),
            'unit_price' => fake()->randomNumber(),
            'product_id' => \App\Models\Product::factory(),
            'advance_purchase_id' => \App\Models\AdvancePurchase::factory(),
        ];
    }
}
