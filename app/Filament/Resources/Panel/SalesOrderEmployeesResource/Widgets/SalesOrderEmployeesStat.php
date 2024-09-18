<?php

namespace App\Filament\Resources\Panel\SalesOrderEmployeesResource\Widgets;

use App\Filament\Resources\Panel\SalesOrderEmployeesResource\Pages\ListSalesOrderEmployees;
use App\Models\SalesOrderEmployee;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SalesOrderEmployeesStat extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListSalesOrderEmployees::class;
    }

    protected function getStats(): array
    {
        $orderData = Trend::model(SalesOrderEmployee::class)
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
            Stat::make('Belum Diperiksa', $this->getPageTableQuery()->whereIn('payment_status', ['1'])->count()),
            Stat::make('Periksa Ulang', $this->getPageTableQuery()->whereIn('payment_status', ['4'])->count()),
        ];
    }
}
