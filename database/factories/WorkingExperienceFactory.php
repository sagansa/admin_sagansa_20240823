<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\WorkingExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkingExperienceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkingExperience::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'place' => fake()->text(255),
            'position' => '{fake()->latitude()},{fake()->longitude()}',
            'salary_per_month' => fake()->randomNumber(),
            'from_date' => fake()->date(),
            'until_date' => fake()->date(),
            'reason' => fake()->text(),
            'employee_id' => \App\Models\Employee::factory(),
        ];
    }
}
