<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\DeliveryService;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeliveryService::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'status' => fake()->numberBetween(1, 2),
        ];
    }
}
