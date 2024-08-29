<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\FuelServicePaymentReceipt;
use Illuminate\Database\Eloquent\Factories\Factory;

class FuelServicePaymentReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FuelServicePaymentReceipt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fuel_service_id' => \App\Models\FuelService::factory(),
            'payment_receipt_id' => \App\Models\PaymentReceipt::factory(),
        ];
    }
}
