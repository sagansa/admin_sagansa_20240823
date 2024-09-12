<?php

namespace App\Filament\Tables;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Columns\StatusColumn;
use App\Models\AdvancePurchase;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class AdvancePurchaseTable
{
    public static function schema(): array
    {
        return [
            ImageOpenUrlColumn::make('image')->visibility('public')
                ->url(fn($record) => asset('storage/' . $record->image)),

            TextColumn::make('store.nickname'),

            TextColumn::make('date'),

            TextColumn::make('detailAdvancePurchaes')
                ->label('Detail Purchases')
                ->html()
                ->formatStateUsing(function (AdvancePurchase $record) {
                    return implode('<br>', $record->detailAdvancePurchases->map(function ($item) {
                        return "{$item->product->name} ({$item->quantity} {$item->product->unit->unit}) {$item->unit_price}";
                    })->toArray());
                })
                ->extraAttributes(['class' => 'whitespace-pre-wrap']),

            CurrencyColumn::make('total_price'),

            StatusColumn::make('status'),
        ];
    }
}
