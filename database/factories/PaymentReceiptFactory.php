<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\PaymentReceipt;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentReceipt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total_amount' => fake()->randomNumber(),
            'transfer_amount' => fake()->randomNumber(),
            'payment_for' => fake()->numberBetween(0, 127),
            'image_adjust' => fake()->text(255),
            'notes' => fake()->text(),
            'supplier_id' => \App\Models\Supplier::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
