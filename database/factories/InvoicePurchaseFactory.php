<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\InvoicePurchase;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoicePurchaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoicePurchase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'taxes' => fake()->randomNumber(),
            'discounts' => fake()->randomNumber(),
            'total_price' => fake()->randomNumber(),
            'payment_status' => fake()->numberBetween(0, 127),
            'order_status' => fake()->numberBetween(0, 127),
            'notes' => fake()->text(),
            'payment_type_id' => \App\Models\PaymentType::factory(),
            'store_id' => \App\Models\Store::factory(),
            'supplier_id' => \App\Models\Supplier::factory(),
            'approved_by_id' => \App\Models\User::factory(),
            'approved_by_id' => \App\Models\User::factory(),
        ];
    }
}
