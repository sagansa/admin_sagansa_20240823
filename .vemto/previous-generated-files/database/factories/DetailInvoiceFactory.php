<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\DetailInvoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailInvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DetailInvoice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity_product' => fake()->randomNumber(1),
            'quantity_invoice' => fake()->randomNumber(1),
            'subtotal_invoice' => fake()->randomNumber(),
            'status' => fake()->word(),
            'invoice_purchase_id' => \App\Models\InvoicePurchase::factory(),
            'detail_request_id' => \App\Models\DetailRequest::factory(),
            'unit_id' => \App\Models\Unit::factory(),
        ];
    }
}
