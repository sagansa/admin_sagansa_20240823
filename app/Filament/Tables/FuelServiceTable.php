<?php

namespace App\Filament\Tables;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\PaymentStatusColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class FuelServiceTable
{
    public static function schema(): array
    {
        return [
                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('fuel_service')
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'fuel',
                            '2' => 'service',
                        }),

                TextColumn::make('supplier.name'),

                TextColumn::make('date'),

                TextColumn::make('vehicle.no_register'),

                TextColumn::make('paymentType.name'),

                TextColumn::make('km')->numeric(thousandsSeparator: '.')->label('km'),

                TextColumn::make('liter'),

                CurrencyColumn::make('amount'),

                TextColumn::make('createdBy.name'),

                PaymentStatusColumn::make('status'),

            ];
    }
}
