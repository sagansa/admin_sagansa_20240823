<?php

namespace App\Filament\Widgets;

use App\Models\StockMonitoring;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductStockWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::user()->hasAnyRole(['super_admin', 'admin']);
    }

    public function getTableQuery(): Builder
    {
        if (!static::canView()) {
            return StockMonitoring::query()->whereRaw('1 = 0');
        }

        return StockMonitoring::query()
            ->with(['stockMonitoringDetails.product' => function ($query) {
                $query->with(['latestStockCard' => function ($query) {
                    $query->select('detail_stock_cards.*', 'stock_cards.date')
                        ->join('stock_cards', 'detail_stock_cards.stock_card_id', '=', 'stock_cards.id')
                        ->latest('stock_cards.date');
                }]);
            }])
            ->select([
                'stock_monitorings.*',
                DB::raw('(
                    SELECT COALESCE(SUM(
                        (
                            SELECT SUM(dsc.quantity)
                            FROM detail_stock_cards dsc
                            JOIN stock_cards sc ON dsc.stock_card_id = sc.id
                            WHERE dsc.product_id = smd.product_id
                            AND sc.date = (
                                SELECT MAX(sc2.date)
                                FROM stock_cards sc2
                                JOIN detail_stock_cards dsc2 ON sc2.id = dsc2.stock_card_id
                                WHERE dsc2.product_id = smd.product_id
                            )
                        ) * smd.coefficient
                    ), 0)
                    FROM stock_monitoring_details smd
                    WHERE stock_monitorings.id = smd.stock_monitoring_id
                ) as total_stock')
            ])
            ->orderByDesc('total_stock');
    }

    protected function getTableColumns(): array
    {
        if (!static::canView()) {
            return [];
        }

        return [
            TextColumn::make('name')
                ->label('Name')
                ->sortable(),

            TextColumn::make('stockMonitoringDetails.product.name')
                ->label('Product')
                ->formatStateUsing(function ($state, $record) {
                    return $record->stockMonitoringDetails->map(function ($detail) {
                        return "{$detail->product->name} (ID: {$detail->product->id})";
                    })->join('<br>');
                })
                ->html()
                ->searchable(),

            TextColumn::make('stockMonitoringDetails.product.latestStockCard.quantity')
                ->label('Quantity')
                ->formatStateUsing(function ($state, $record) {
                    return $record->stockMonitoringDetails->map(function ($detail) {
                        $latestDate = $detail->product->latestStockCard?->date;
                        $totalQuantity = $detail->product->detailStockCards()
                            ->whereHas('stockCard', function ($query) use ($latestDate) {
                                $query->where('date', $latestDate);
                            })
                            ->sum('quantity');

                        return "ID {$detail->product->id}: " . number_format($totalQuantity, 0, ',', '.');
                    })->join('<br>');
                })
                ->html(),

            TextColumn::make('total_stock')
                ->label('Total Stock')
                ->sortable()
                ->alignRight()
                ->formatStateUsing(function ($state, $record) {
                    $color = $state < $record->quantity_low ? 'text-danger-500' : 'text-success-500';
                    return '<span class="' . $color . '">' . number_format($state, 0, ',', '.') . '</span>';
                })
                ->html(),

            TextColumn::make('quantity_low')
                ->label('Quantity Low')
                ->sortable()
                ->alignRight()
                ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),

            TextColumn::make('status_stock')
                ->label('Status')
                ->formatStateUsing(function ($state, $record) {
                    $status = $record->total_stock < $record->quantity_low ? 'Low Stock' : 'Normal';
                    $color = $record->total_stock < $record->quantity_low ? 'text-danger-500' : 'text-success-500';
                    return '<span class="font-medium' . $color . '">' . $status . '</span>';
                })
                ->html(),

            TextColumn::make('stockMonitoringDetails.product.latestStockCard.date')
                ->label('Tanggal')
                ->formatStateUsing(function ($state, $record) {
                    $latestDate = $record->stockMonitoringDetails
                        ->map(function ($detail) {
                            return $detail->product->latestStockCard?->date;
                        })
                        ->filter()
                        ->max();

                    if (!$latestDate) return '-';

                    return $latestDate instanceof \Carbon\Carbon
                        ? $latestDate->format('d/m/Y')
                        : \Carbon\Carbon::parse($latestDate)->format('d/m/Y');
                })
                ->sortable(),

            TextColumn::make('stockMonitoringDetails.coefficient')
                ->label('Coefficient')
                ->formatStateUsing(function ($state, $record) {
                    return $record->stockMonitoringDetails->map(function ($detail) {
                        return "ID {$detail->product->id}: {$detail->coefficient}";
                    })->join('<br>');
                })
                ->html(),

            TextColumn::make('stockMonitoringDetails.product.unit.unit')
                ->label('Unit')
                ->formatStateUsing(function ($state, $record) {
                    return $record->stockMonitoringDetails->map(function ($detail) {
                        return "ID {$detail->product->id}: {$detail->product->unit->unit}";
                    })->join('<br>');
                })
                ->html(),
        ];
    }

    protected function getTableFilters(): array
    {
        if (!static::canView()) {
            return [];
        }

        return [
            // Tambahkan filter jika diperlukan
        ];
    }
}
