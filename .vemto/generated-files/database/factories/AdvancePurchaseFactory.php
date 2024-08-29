<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\AdvancePurchase;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvancePurchaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdvancePurchase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'subtotal_price' => fake()->randomNumber(),
            'discount_price' => fake()->randomNumber(),
            'total_price' => fake()->randomNumber(),
            'status' => fake()->word(),
            'notes' => fake()->text(),
            'store_id' => \App\Models\Store::factory(),
            'user_id' => \App\Models\User::factory(),
            'supplier_id' => \App\Models\Supplier::factory(),
            'cash_advance_id' => \App\Models\CashAdvance::factory(),
        ];
    }
}
