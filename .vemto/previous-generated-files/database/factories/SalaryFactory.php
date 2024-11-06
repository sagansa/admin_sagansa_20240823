<?php

namespace Database\Factories;

use App\Models\Salary;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Salary::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'period_start' => fake()->date(),
            'period_end' => fake()->date(),
            'years_of_service' => fake()->randomNumber(0),
            'total_work_days' => fake()->randomNumber(0),
            'total_hours' => fake()->randomNumber(0),
            'rate_per_hour' => fake()->randomNumber(0),
            'base_salary' => fake()->randomNumber(0),
            'allowances' => [],
            'deductions' => [],
            'total_salary' => fake()->randomNumber(0),
            'status' => fake()->word(),
            'approved_at' => fake()->dateTime(),
            'paid_at' => fake()->dateTime(),
            'payment_method' => fake()->text(255),
            'payment_reference' => fake()->text(255),
            'notes' => fake()->text(),
            'deleted_at' => fake()->dateTime(),
            'user_id' => \App\Models\User::factory(),
            'approved_by_id' => \App\Models\User::factory(),
            'salary_rate_id' => \App\Models\SalaryRate::factory(),
        ];
    }
}
