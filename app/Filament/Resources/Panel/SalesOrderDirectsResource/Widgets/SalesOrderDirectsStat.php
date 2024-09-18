<?php

namespace App\Filament\Resources\Panel\SalesOrderDirectsResource\Widgets;

use App\Filament\Resources\Panel\SalesOrderDirectsResource\Pages\ListSalesOrderDirects;
use App\Models\SalesOrderDirect;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SalesOrderDirectsStat extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListSalesOrderDirects::class;
    }

    protected function getStats(): array
    {
        $orderData = Trend::model(SalesOrderDirect::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            Stat::make('Orders', $this->getPageTableQuery()->count())
                ->chart(
                    $orderData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            Stat::make('Order Status - Belum Dikirim', $this->getPageTableQuery()->whereIn('delivery_status', ['1'])->count()),
            Stat::make('Payment Status - Belum Diperiksa', $this->getPageTableQuery()->whereIn('payment_status', ['1'])->count()),
            // Stat::make('Belum Dikirim', $this->getPageTableQuery()->whereIn('delivery_status', ['1'])->count()),
            // Stat::make('Average price', number_format($this->getPageTableQuery()->avg('total_price'), 2)),
        ];
    }
}
