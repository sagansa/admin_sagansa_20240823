<?php

namespace App\Filament\Tables;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Columns\PaymentStatusColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class FuelServiceTable
{
    public static function schema(): array
    {
        return [
                ImageOpenUrlColumn::make('image')
                    ->url(fn($record) => asset('storage/' . $record->image)),

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

                TextColumn::make('createdBy.name')
                    ->hidden(fn () => !Auth::user()->hasRole('admin')),

                PaymentStatusColumn::make('status'),

            ];
    }
}
