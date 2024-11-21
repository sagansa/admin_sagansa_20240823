<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\StockMonitoring;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMonitoringFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockMonitoring::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'quantity_low' => fake()->randomNumber(),
            'category' => fake()->word(),
            'unit_id' => \App\Models\Unit::factory(),
        ];
    }
}
