<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\EmployeeConsumption;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeConsumptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeeConsumption::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'store_id' => \App\Models\Store::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
