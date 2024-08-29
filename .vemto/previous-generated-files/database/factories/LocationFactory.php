<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'contact_person_name' => fake()->text(255),
            'contact_person_number' => fake()->text(255),
            'address' => fake()->address(),
            'store_id' => \App\Models\Store::factory(),
            'province_id' => \App\Models\Province::factory(),
            'city_id' => \App\Models\City::factory(),
            'district_id' => \App\Models\District::factory(),
            'subdistrict_id' => \App\Models\Subdistrict::factory(),
            'postal_code_id' => \App\Models\PostalCode::factory(),
        ];
    }
}
