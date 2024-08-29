<?php

namespace App\Filament\Tables;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\PaymentStatusColumn;
use Filament\Tables\Columns\TextColumn;

class DailySalaryTable
{
    public static function schema(): array
    {
        return [
            TextColumn::make('createdBy.name')
                ->label('For'),

            TextColumn::make('store.name'),

            TextColumn::make('shiftStore.name'),

            TextColumn::make('date'),

            CurrencyColumn::make('amount'),

            PaymentStatusColumn::make('status'),

            TextColumn::make('paymentType.name'),
        ];
    }
}
