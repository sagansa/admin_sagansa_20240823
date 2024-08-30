<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nickname' => fake()->username(),
            'no_telp' => fake()->randomNumber(),
            'email' => fake()
                ->unique()
                ->safeEmail(),
            'status' => fake()->numberBetween(1,2),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
