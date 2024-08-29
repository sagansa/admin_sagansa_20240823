<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'address' => fake()->address(),
            'bank_account_name' => fake()->text(255),
            'bank_account_no' => fake()->text(255),
            'status' => fake()->word(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'province_id' => \App\Models\Province::factory(),
            'city_id' => \App\Models\City::factory(),
            'district_id' => \App\Models\District::factory(),
            'subdistrict_id' => \App\Models\Subdistrict::factory(),
            'bank_id' => \App\Models\Bank::factory(),
            'user_id' => \App\Models\User::factory(),
            'postal_code_id' => \App\Models\PostalCode::factory(),
        ];
    }
}
