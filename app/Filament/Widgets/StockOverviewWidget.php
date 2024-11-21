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

    public static function canView(): bool
    {
        return Auth::user()->hasAnyRole(['super-admin', 'admin']);
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
        // Query total stock (yang sudah ada sebelumnya)
        $totalStock = DB::table('stock_monitoring_details as smd')
            ->select(DB::raw('COALESCE(SUM(
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
            ), 0) as total'))
            ->value('total');

        // Query total produk
        $totalProducts = DB::table('stock_monitorings')
            // ->where('request', '1')
            ->count();

        // Query jumlah produk yang low stock
        $lowStockProducts = DB::table('stock_monitoring_details as smd')
            ->join('stock_monitorings as sm', 'smd.stock_monitoring_id', '=', 'sm.id')
            ->join('products as p', 'smd.product_id', '=', 'p.id')
            ->where('p.request', '1')
            ->select('p.id')
            ->groupBy('p.id')
            ->havingRaw('
                SUM(
                    (
                        SELECT COALESCE(SUM(dsc.quantity), 0)
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
                ) < MIN(sm.quantity_low)
            ')
            ->count();

        return [
            'totalStock' => $totalStock,
            'totalProducts' => $totalProducts,
            'lowStockProducts' => $lowStockProducts,
        ];
    }
}
