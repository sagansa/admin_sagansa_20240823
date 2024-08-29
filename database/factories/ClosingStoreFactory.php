<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ClosingStore;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClosingStoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClosingStore::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'cash_from_yesterday' => fake()->randomNumber(),
            'cash_for_tomorrow' => fake()->randomNumber(),
            'total_cash_transfer' => fake()->randomNumber(),
            'status' => fake()->word(),
            'notes' => fake()->text(),
            'shift_store_id' => \App\Models\ShiftStore::factory(),
            'store_id' => \App\Models\Store::factory(),
            'approved_by_id' => \App\Models\User::factory(),
            'created_by_id' => \App\Models\User::factory(),
            'approved_by_id' => \App\Models\User::factory(),
        ];
    }
}
