<?php

namespace App\Filament\Resources\Panel\SalesOrderOnlinesResource\Widgets;

use App\Filament\Resources\Panel\SalesOrderOnlinesResource\Pages\ListSalesOrderOnlines;
use App\Models\SalesOrderOnline;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SalesOrderOnlinesStat extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListSalesOrderOnlines::class;
    }

    protected function getStats(): array
    {
        $orderData = Trend::model(SalesOrderOnline::class)
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
            Stat::make('Belum Dikirim', $this->getPageTableQuery()->whereIn('delivery_status', ['1'])->count()),
            Stat::make('Siap Dikirim', $this->getPageTableQuery()->whereIn('delivery_status', ['4'])->count()),
            // Stat::make('Belum Dikirim', $this->getPageTableQuery()->whereIn('delivery_status', ['1'])->count()),
            // Stat::make('Average price', number_format($this->getPageTableQuery()->avg('total_price'), 2)),
        ];
    }
}
