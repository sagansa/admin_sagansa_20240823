<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'identity_no' => fake()->text(16),
            'nickname' => fake()->text(20),
            'no_telp' => fake()->text(15),
            'birth_place' => fake()->text(25),
            'birth_date' => fake()->date(),
            'fathers_name' => fake()->text(100),
            'mothers_name' => fake()->text(100),
            'address' => fake()->address(),
            'parents_no_telp' => fake()->text(15),
            'siblings_name' => fake()->text(100),
            'siblings_no_telp' => fake()->text(15),
            'bpjs' => fake()->boolean(),
            'bank_account_no' => fake()->text(255),
            'acceptance_date' => fake()->date(),
            'signs' => fake()->text(255),
            'notes' => fake()->text(),
            'image_identity_id' => fake()->text(255),
            'image_selfie' => fake()->text(255),
            'gender' => \Arr::random(['male', 'female', 'other']),
            'religion' => fake()->numberBetween(0, 127),
            'driving_license' => fake()->numberBetween(0, 127),
            'marital_status' => fake()->numberBetween(0, 127),
            'level_of_education' => fake()->numberBetween(0, 127),
            'major' => fake()->text(10),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'user_id' => \App\Models\User::factory(),
            'bank_id' => \App\Models\Bank::factory(),
            'employee_status_id' => \App\Models\EmployeeStatus::factory(),
            'province_id' => \App\Models\Province::factory(),
            'city_id' => \App\Models\City::factory(),
            'district_id' => \App\Models\District::factory(),
            'subdistrict_id' => \App\Models\Subdistrict::factory(),
            'postal_code_id' => \App\Models\PostalCode::factory(),
        ];
    }
}
