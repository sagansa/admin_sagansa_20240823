<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\VehicleCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleCertificateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VehicleCertificate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bpkb' => fake()->numberBetween(0, 127),
            'stnk' => fake()->numberBetween(0, 127),
            'name' => fake()->name(),
            'brand' => fake()->text(255),
            'type' => fake()->word(),
            'category' => fake()->text(255),
            'model' => fake()->text(255),
            'manufacture_year' => fake()->year(),
            'cylinder_capacity' => fake()->text(255),
            'vehicle_identity_no' => fake()->text(255),
            'engine_no' => fake()->text(255),
            'color' => fake()->hexcolor(),
            'type_fuel' => fake()->text(255),
            'lisence_plate_color' => fake()->text(255),
            'registration_year' => fake()->text(255),
            'bpkb_no' => fake()->text(255),
            'location_code' => fake()->text(255),
            'registration_queue_no' => fake()->text(255),
            'notes' => fake()->text(),
            'vehicle_id' => \App\Models\Vehicle::factory(),
        ];
    }
}
