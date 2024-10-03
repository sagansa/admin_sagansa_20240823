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
            TextColumn::make('date'),

            TextColumn::make('store.nickname'),

            TextColumn::make('detailStockCards', 'Detail Stock Cards')
                ->label('Stocks')
                ->html()
                ->formatStateUsing(function ($record) {
                    return implode('<br>', $record->detailStockCards->map(function ($detailStockCard) {
                        return "{$detailStockCard->product->name} = {$detailStockCard->quantity} {$detailStockCard->product->unit->unit}";
                    })->toArray());
                })
                ->extraAttributes(['class' => 'whitespace-pre-wrap']),

            TextColumn::make('user.name')
                ->hidden(fn () => Auth::user()->hasRole('staff'))
                ->toggleable(isToggledHiddenByDefault: true),

            StatusColumn::make('status'),
        ];
    }
}
