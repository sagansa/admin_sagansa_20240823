<?php

namespace Database\Factories;

use App\Models\SalaryRate;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaryRateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SalaryRate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'effective_date' => fake()->date(),
            'notes' => fake()->text(),
            'deleted_at' => fake()->dateTime(),
        ];
    }
}