<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\TransferStock;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransferStockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransferStock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'status' => fake()->word(),
            'notes' => fake()->text(),
            'to_store_id' => \App\Models\Store::factory(),
            'to_store_id' => \App\Models\Store::factory(),
            'sent_by_id' => \App\Models\User::factory(),
            'received_by_id' => \App\Models\User::factory(),
            'sent_by_id' => \App\Models\User::factory(),
        ];
    }
}
