<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nickname' => fake()->text(255),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()
                ->unique()
                ->safeEmail(),
            'website' => fake()->text(255),
            'logo' => fake()->text(255),
            'description' => fake()->sentence(15),
            'is_active' => fake()->boolean(),
            'deleted_at' => fake()->dateTime(),
        ];
    }
}
