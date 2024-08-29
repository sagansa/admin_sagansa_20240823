<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => fake()->slug(),
            'sku' => fake()->text(255),
            'barcode' => fake()->text(255),
            'description' => fake()->sentence(15),
            'request' => fake()->numberBetween(0, 127),
            'remaining' => fake()->numberBetween(0, 127),
            'deleted_at' => fake()->dateTime(),
            'user_id' => \App\Models\User::factory(),
            'unit_id' => \App\Models\Unit::factory(),
            'payment_type_id' => \App\Models\PaymentType::factory(),
            'material_group_id' => \App\Models\MaterialGroup::factory(),
            'online_category_id' => \App\Models\OnlineCategory::factory(),
        ];
    }
}
