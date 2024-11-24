<?php

namespace App\Filament\Tables;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\PaymentStatusColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;

class DailySalaryTable
{
    public static function schema(): array
    {
        return [
            TextColumn::make('createdBy.name')
                ->label('For'),

            TextColumn::make('store.nickname')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('shiftStore.name'),

            TextColumn::make('date'),

            CurrencyColumn::make('amount')
                ->summarize(Sum::make()
                    ->prefix('Rp ')
                    ->label('')
                    ->numeric(thousandsSeparator: '.')),

            PaymentStatusColumn::make('status'),

            TextColumn::make('paymentType.name')
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
