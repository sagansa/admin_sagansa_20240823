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
                        })
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('supplier.name')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('date'),

                TextColumn::make('vehicle.no_register'),

                TextColumn::make('vehicle.store.nickname')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('paymentType.name'),

                TextColumn::make('Rp/liter')
                    ->getStateUsing(function ($record) {
                        if ($record->fuel_service === 1) {
                            return $record->liter > 0 ? $record->amount / $record->liter : 0;
                        } elseif ($record->fuel_service === 2) {
                            return '';
                        }
                    })
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->suffix(' /l'),

                TextColumn::make('km')->numeric(thousandsSeparator: '.')->label('km'),

                TextColumn::make('liter')
                    ->toggleable(isToggledHiddenByDefault: true),

                CurrencyColumn::make('amount')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('createdBy.name')
                    ->hidden(fn () => !Auth::user()->hasRole('admin'))
                    ->toggleable(isToggledHiddenByDefault: false),

                PaymentStatusColumn::make('status'),

            ];
    }
}
