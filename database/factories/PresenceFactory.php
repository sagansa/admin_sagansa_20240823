<?php

namespace Database\Factories;

use App\Models\Presence;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PresenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Presence::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->word(),
            'image_in' => fake()->text(255),
            'start_date_time' => fake()->dateTime(),
            'latitude_in' => fake()->randomNumber(2),
            'longitude_in' => fake()->randomNumber(2),
            'image_out' => fake()->text(255),
            'end_date_time' => fake()->dateTime(),
            'latitude_out' => fake()->randomNumber(2),
            'longitude_out' => fake()->randomNumber(2),
            'created_by_id' => \App\Models\User::factory(),
            'approved_by_id' => \App\Models\User::factory(),
        ];
    }
}
