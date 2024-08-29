<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\SalesOrderEmployee;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesOrderEmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SalesOrderEmployee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'for' => '1',
            'delivery_date' => fake()->date(),
            'image_payment' => fake()->text(255),
            'payment_status' => fake()->numberBetween(0, 127),
            'delivery_status' => fake()->numberBetween(0, 127),
            'shipping_cost' => fake()->randomNumber(),
            'receipt_no' => fake()->text(255),
            'image_delivery' => fake()->text(255),
            'notes' => fake()->text(),
            'total_price' => fake()->randomNumber(),
            'delivery_service_id' => \App\Models\DeliveryService::factory(),
            'transfer_to_account_id' => \App\Models\TransferToAccount::factory(),
            'store_id' => \App\Models\Store::factory(),
            'ordered_by_id' => \App\Models\User::factory(),
            'assigned_by_id' => \App\Models\User::factory(),
            'online_shop_provider_id' => \App\Models\OnlineShopProvider::factory(),
            'delivery_address_id' => \App\Models\DeliveryAddress::factory(),
        ];
    }
}
