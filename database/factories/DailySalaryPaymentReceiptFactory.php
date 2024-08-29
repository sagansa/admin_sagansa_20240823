<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\DailySalaryPaymentReceipt;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailySalaryPaymentReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DailySalaryPaymentReceipt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'daily_salary_id' => \App\Models\DailySalary::factory(),
            'payment_receipt_id' => \App\Models\PaymentReceipt::factory(),
        ];
    }
}
