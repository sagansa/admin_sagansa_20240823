<?php

namespace Database\Factories;

use App\Models\Cashless;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashlessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cashless::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bruto_apl' => fake()->randomNumber(),
            'netto_apl' => fake()->randomNumber(),
            'bruto_real' => fake()->randomNumber(),
            'netto_real' => fake()->randomNumber(),
            'image_canceled' => fake()->text(255),
            'canceled' => fake()->randomNumber(0),
            'account_cashless_id' => \App\Models\AccountCashless::factory(),
            'closing_store_id' => \App\Models\ClosingStore::factory(),
        ];
    }
}
