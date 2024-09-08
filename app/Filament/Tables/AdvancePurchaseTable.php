<?php

namespace App\Filament\Tables;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\StatusColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class AdvancePurchaseTable
{
    public static function schema(): array
    {
        return [
            ImageColumn::make('image')->visibility('public'),

            TextColumn::make('store.nickname'),

            TextColumn::make('date'),

            CurrencyColumn::make('total_price'),

            StatusColumn::make('status'),
        ];
    }
}
