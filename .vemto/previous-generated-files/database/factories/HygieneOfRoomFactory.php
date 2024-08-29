<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\HygieneOfRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

class HygieneOfRoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HygieneOfRoom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hygiene_id' => \App\Models\Hygiene::factory(),
            'room_id' => \App\Models\Room::factory(),
        ];
    }
}
