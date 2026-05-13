<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\StockMonitoring;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected ?string $pollingInterval = '60s';

    public static function canView(): bool
    {
        return Auth::user()->hasAnyRole(['super_admin', 'admin']);
    }

    protected function getStats(): array
    {
        if (!static::canView()) {
            return [];
        }

        $stats = $this->calculateStats();

        return [
            Stat::make('Total Stock', number_format($stats['totalStock'], 0, ',', '.'))
                ->description('Total keseluruhan stock')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('success'),

            Stat::make('Total Products', number_format($stats['totalProducts'], 0, ',', '.'))
                ->description('Jumlah produk aktif')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make('Low Stock Products', number_format($stats['lowStockProducts'], 0, ',', '.'))
                ->description('Jumlah produk low stock')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
        ];
    }

    protected function calculateStats(): array
    {
        // optimized query: Get latest stock quantity for each product first
        $latestStockSubquery = DB::table('detail_stock_cards as dsc')
            ->join('stock_cards as sc', 'dsc.stock_card_id', '=', 'sc.id')
            ->join(DB::raw('(SELECT dsc2.product_id, MAX(sc2.date) as max_date FROM detail_stock_cards dsc2 JOIN stock_cards sc2 ON dsc2.stock_card_id = sc2.id GROUP BY dsc2.product_id) as latest'), function($join) {
                $join->on('dsc.product_id', '=', 'latest.product_id')
                     ->on('sc.date', '=', 'latest.max_date');
            })
            ->select('dsc.product_id', DB::raw('SUM(dsc.quantity) as current_quantity'))
            ->groupBy('dsc.product_id');

        // Total Stock
        $totalStock = DB::table('stock_monitoring_details as smd')
            ->joinSub($latestStockSubquery, 'ls', 'smd.product_id', '=', 'ls.product_id')
            ->select(DB::raw('SUM(ls.current_quantity * smd.coefficient) as total'))
            ->value('total') ?? 0;

        // Total Products (Active monitorings)
        $totalProducts = DB::table('stock_monitorings')->count();

        // Low Stock Products
        $lowStockProducts = DB::table('stock_monitorings as sm')
            ->join(DB::raw('(
                SELECT smd.stock_monitoring_id, SUM(ls.current_quantity * smd.coefficient) as current_total
                FROM stock_monitoring_details smd
                JOIN (' . $latestStockSubquery->toSql() . ') ls ON smd.product_id = ls.product_id
                GROUP BY smd.stock_monitoring_id
            ) as monitoring_stock'), 'sm.id', '=', 'monitoring_stock.stock_monitoring_id')
            ->mergeBindings($latestStockSubquery) // Important for toSql()
            ->whereRaw('monitoring_stock.current_total < sm.quantity_low')
            ->count();

        return [
            'totalStock' => $totalStock,
            'totalProducts' => $totalProducts,
            'lowStockProducts' => $lowStockProducts,
        ];
    }
}
