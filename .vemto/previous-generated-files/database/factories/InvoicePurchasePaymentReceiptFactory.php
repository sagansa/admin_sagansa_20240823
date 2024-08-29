<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\InvoicePurchasePaymentReceipt;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoicePurchasePaymentReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoicePurchasePaymentReceipt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_purchase_id' => \App\Models\InvoicePurchase::factory(),
            'payment_receipt_id' => \App\Models\PaymentReceipt::factory(),
        ];
    }
}
