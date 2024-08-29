<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\StoreCashless;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreCashlessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StoreCashless::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'status' => fake()->word(),
        ];
    }
}
