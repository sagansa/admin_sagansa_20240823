<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\TransferCardStore;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransferCardStoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransferCardStore::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'image' => fake()->word(),
            'status' => fake()->word(),
            'from_store_id' => \App\Models\Store::factory(),
            'to_store_id' => \App\Models\Store::factory(),
            'sent_by_id' => \App\Models\User::factory(),
            'received_by_id' => \App\Models\User::factory(),
        ];
    }
}
