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
    protected int|string|array $columnSpan = 'full';
    protected ?string $pollingInterval = '60s';

    public static function canView(): bool
    {
        return Auth::user()->hasAnyRole(['super_admin', 'admin']);
    }

    public function getTableQuery(): Builder
    {
        if (!static::canView()) {
            return StockMonitoring::query()->whereRaw('1 = 0');
        }

        // Optimized subquery for latest stock per product
        $latestStockSubquery = DB::table('detail_stock_cards as dsc')
            ->join('stock_cards as sc', 'dsc.stock_card_id', '=', 'sc.id')
            ->join(DB::raw('(SELECT dsc2.product_id, MAX(sc2.date) as max_date FROM detail_stock_cards dsc2 JOIN stock_cards sc2 ON dsc2.stock_card_id = sc2.id GROUP BY dsc2.product_id) as latest'), function($join) {
                $join->on('dsc.product_id', '=', 'latest.product_id')
                     ->on('sc.date', '=', 'latest.max_date');
            })
            ->select('dsc.product_id', DB::raw('SUM(dsc.quantity) as current_quantity'), DB::raw('MAX(sc.date) as latest_date'))
            ->groupBy('dsc.product_id');

        return StockMonitoring::query()
            ->with(['stockMonitoringDetails.product.unit'])
            ->leftJoin(DB::raw('(
                SELECT smd.stock_monitoring_id, 
                       SUM(ls.current_quantity * smd.coefficient) as calculated_total_stock,
                       MAX(ls.latest_date) as last_stock_date
                FROM stock_monitoring_details smd
                JOIN (' . $latestStockSubquery->toSql() . ') ls ON smd.product_id = ls.product_id
                GROUP BY smd.stock_monitoring_id
            ) as totals'), 'stock_monitorings.id', '=', 'totals.stock_monitoring_id')
            ->mergeBindings($latestStockSubquery)
            ->select('stock_monitorings.*', 'totals.calculated_total_stock', 'totals.last_stock_date')
            ->orderByDesc('calculated_total_stock');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Name')
                ->sortable()
                ->searchable(),

            TextColumn::make('stock_monitoring_details')
                ->label('Products')
                ->formatStateUsing(function ($record) {
                    return $record->stockMonitoringDetails->map(function ($detail) {
                        return "{$detail->product->name} (Coeff: {$detail->coefficient})";
                    })->join('<br>');
                })
                ->html(),

            TextColumn::make('calculated_total_stock')
                ->label('Total Stock')
                ->sortable()
                ->alignRight()
                ->formatStateUsing(function ($state, $record) {
                    $color = $state < $record->quantity_low ? 'text-danger-600 font-bold' : 'text-success-600';
                    $unit = $record->stockMonitoringDetails->first()?->product?->unit?->nickname ?? '';
                    
                    return sprintf(
                        '<span class="%s">%s %s</span>',
                        $color,
                        number_format($state ?? 0, 0, ',', '.'),
                        $unit
                    );
                })
                ->html(),

            TextColumn::make('quantity_low')
                ->label('Low Threshold')
                ->sortable()
                ->alignRight()
                ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),

            TextColumn::make('status')
                ->label('Status')
                ->formatStateUsing(function ($record) {
                    $isLow = ($record->calculated_total_stock ?? 0) < $record->quantity_low;
                    return $isLow 
                        ? '<span class="px-2 py-1 text-xs font-bold text-white bg-danger-600 rounded-full">LOW STOCK</span>'
                        : '<span class="px-2 py-1 text-xs font-bold text-white bg-success-600 rounded-full">NORMAL</span>';
                })
                ->html(),

            TextColumn::make('last_stock_date')
                ->label('Last Updated')
                ->dateTime('d/m/Y')
                ->sortable(),
        ];
    }
}
