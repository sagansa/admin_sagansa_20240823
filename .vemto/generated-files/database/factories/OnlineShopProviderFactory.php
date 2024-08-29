<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\OnlineShopProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class OnlineShopProviderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OnlineShopProvider::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
        ];
    }
}
