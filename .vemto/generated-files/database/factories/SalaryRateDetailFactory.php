<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\SalaryRateDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaryRateDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SalaryRateDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'years_of_service' => fake()->randomNumber(0),
            'rate_per_hour' => fake()->randomNumber(0),
            'salary_rate_id' => \App\Models\SalaryRate::factory(),
        ];
    }
}
