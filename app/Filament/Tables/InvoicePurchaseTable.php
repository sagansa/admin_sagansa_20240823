<?php

namespace App\Filament\Tables;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\PaymentStatusColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class InvoicePurchaseTable
{
    public static function schema(): array
    {
        return [
            ImageColumn::make('image')->visibility('public'),

            TextColumn::make('paymentType.name'),

            TextColumn::make('store.nickname'),

            TextColumn::make('supplier.name'),

            TextColumn::make('date'),

            CurrencyColumn::make('total_price'),

            TextColumn::make('payment_status')
                ->badge()
                ->color(
                    fn(string $state): string => match ($state) {
                        '1' => 'warning',
                        '2' => 'success',
                        '3' => 'danger',
                        default => $state,
                    }
                )
                ->formatStateUsing(
                    fn(string $state): string => match ($state) {
                        '1' => 'belum dibayar',
                        '2' => 'sudah dibayar',
                        '3' => 'tidak valid',
                        default => $state,
                    }
                ),

            TextColumn::make('order_status')
                ->badge()
                ->color(
                    fn(string $state): string => match ($state) {
                        '1' => 'warning',
                        '2' => 'success',
                        '3' => 'danger',
                        default => $state,
                    }
                )
                ->formatStateUsing(
                    fn(string $state): string => match ($state) {
                        '1' => 'belum diterima',
                        '2' => 'sudah diterima',
                        '3' => 'dikembalikan',
                        default => $state,
                    }
                ),

            TextColumn::make('createdBy.name'),
        ];
    }

}
