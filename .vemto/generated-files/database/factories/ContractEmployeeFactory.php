<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ContractEmployee;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractEmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContractEmployee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from_date' => fake()->date(),
            'until_date' => fake()->date(),
            'nominal_guarantee' => fake()->randomNumber(),
            'guarantee' => fake()->numberBetween(0, 127),
            'employee_id' => fake()->randomNumber(),
            'guaranteed_return' => fake()->boolean(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
