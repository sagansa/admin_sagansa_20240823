<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\DeliveryAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeliveryAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'recipients_name' => fake()->text(255),
            'recipients_telp_no' => fake()->text(255),
            'address' => fake()->address(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'for' => fake()->numberBetween(0, 127),
            'province_id' => \App\Models\Province::factory(),
            'city_id' => \App\Models\City::factory(),
            'district_id' => \App\Models\District::factory(),
            'subdistrict_id' => \App\Models\Subdistrict::factory(),
            'postal_code_id' => \App\Models\PostalCode::factory(),
        ];
    }
}
