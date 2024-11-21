<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\StockMonitoringDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMonitoringDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockMonitoringDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'coefisien' => fake()->word(),
            'product_id' => \App\Models\Product::factory(),
            'stock_monitoring_id' => \App\Models\StockMonitoring::factory(),
        ];
    }
}
