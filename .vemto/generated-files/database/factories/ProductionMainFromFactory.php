<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ProductionMainFrom;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionMainFromFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductionMainFrom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'production_id' => \App\Models\Production::factory(),
            'detail_invoice_id' => \App\Models\DetailInvoice::factory(),
        ];
    }
}
