<?php

namespace App\Filament\Forms;

use App\Filament\Forms\DateInput;
use App\Filament\Forms\StockRepeaterForm;
use App\Filament\Forms\StoreSelect;
use Filament\Schemas\Components\Section;

class StockCardForm
{
    public static function getStockCardStorage(): array
    {
        return [
            Section::make('Informasi Laporan')
                ->description('Pilih tanggal dan lokasi toko untuk pelaporan stok.')
                ->icon('heroicon-o-calendar')
                ->schema([
                    DateInput::make('date')
                        ->maxDate(now())
                        ->columnSpan(1),

                    StoreSelect::make('store_id')
                        ->columnSpan(1),
                ])->columns(2),

            Section::make('Detail Inventori')
                ->description('Masukkan jumlah stok fisik yang tersedia saat ini.')
                ->icon('heroicon-o-circle-stack')
                ->schema([
                    StockRepeaterForm::getStorageRepeater(),
                ])
        ];
    }

    public static function getStockCardRemaining(): array
    {
        return [
            Section::make('Informasi Laporan')
                ->description('Pilih tanggal dan lokasi toko untuk pelaporan sisa stok.')
                ->icon('heroicon-o-calendar')
                ->schema([
                    DateInput::make('date')
                        ->columnSpan(1),

                    StoreSelect::make('store_id')
                        ->columnSpan(1),
                ])->columns(2),

            Section::make('Detail Sisa Stok')
                ->description('Masukkan sisa stok yang belum terjual.')
                ->icon('heroicon-o-archive-box')
                ->schema([
                    StockRepeaterForm::getRemainingRepeater(),
                ])
        ];
    }
}
