<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\MonthlySalary;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonthlySalaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MonthlySalary::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->randomNumber(),
        ];
    }
}
