<?php

namespace Database\Factories;

use App\Models\FuelService;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class FuelServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FuelService::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'fuel_service' => fake()->numberBetween(0, 127),
            'km' => fake()->randomNumber(),
            'liter' => fake()->randomNumber(1),
            'amount' => fake()->randomNumber(),
            'status' => fake()->word(),
            'notes' => fake()->text(),
            'supplier_id' => \App\Models\Supplier::factory(),
            'vehicle_id' => \App\Models\Vehicle::factory(),
            'payment_type_id' => \App\Models\PaymentType::factory(),
            'approved_by_id' => \App\Models\User::factory(),
            'approved_by_id' => \App\Models\User::factory(),
        ];
    }
}
