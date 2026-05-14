<?php

namespace App\Filament\Tables;

use App\Filament\Columns\StatusColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class StockCardTable
{
    public static function schema($modelClass): array
    {
        return [
            \Filament\Tables\Columns\Layout\Split::make([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('store.nickname')
                    ->label('Toko')
                    ->searchable()
                    ->sortable()
                    ->color('gray'),

                StatusColumn::make('status')
                    ->label('Status'),
            ]),

            \Filament\Tables\Columns\Layout\Panel::make([
                \Filament\Tables\Columns\Layout\Stack::make([
                    TextColumn::make('rincian_stok_tabel')
                        ->label('Rincian Stok')
                        ->getStateUsing(fn ($record) => $record)
                        ->html()
                        ->formatStateUsing(function ($state) {
                            $items = $state->detailStockCards->map(function ($detailStockCard) {
                                $productName = $detailStockCard->product?->name ?? 'Unknown';
                                $qty = number_format($detailStockCard->quantity, 0, ',', '.');
                                $unit = $detailStockCard->product?->unit?->unit ?? '';
                                return "<div style='padding: 6px 0; border-bottom: 1px solid rgba(156, 163, 175, 0.2); font-size: 11px; line-height: 1.4;'>
                                            <span style='opacity: 0.8;'>{$productName}</span>
                                            <span style='font-weight: bold;'> = {$qty} <small style='font-weight: normal; opacity: 0.6;'>{$unit}</small></span>
                                        </div>";
                            })->implode('');

                            return "<div style='display: grid; grid-template-columns: repeat(3, 1fr); gap: 0 40px;'>{$items}</div>";
                        })
                ])
            ])->collapsible(),

            TextColumn::make('user.name')
                ->label('Pelapor')
                ->hidden(fn () => Auth::user()->hasRole('staff'))
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
