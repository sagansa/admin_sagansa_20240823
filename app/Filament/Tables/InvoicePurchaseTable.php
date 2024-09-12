<?php

namespace App\Filament\Tables;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Columns\PaymentStatusColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class InvoicePurchaseTable
{
    public static function schema(): array
    {
        return [
            ImageOpenUrlColumn::make('image')
                ->visibility('public')
                ->url(fn($record) => asset('storage/' . $record->image)),

            TextColumn::make('paymentType.name'),

            TextColumn::make('store.nickname'),

            // TextColumn::make('supplier.supplier_name'),

            // TextColumn::make('supplier')
            //     ->formatStateUsing(
            //         fn($record): string => '<ul><li>' . implode('</li><li>', [
            //             $record->supplier->name,
            //             $record->supplier->bank->name,
            //             $record->supplier->bank_account_name,
            //             $record->supplier->bank_account_no,
            //         ]) . '</li></ul>'
            //     )->html(),

            TextColumn::make('supplier')
                ->formatStateUsing(
                    fn($record): string => '<ul>' . implode('', [
                        '<li>Nama: ' . $record->supplier->name . '</li>',
                        '<li>Bank: ' . ($record->supplier->bank ? $record->supplier->bank->name : 'tidak tersedia') . '</li>',
                        '<li>Nama Rekening: ' . ($record->supplier->bank_account_name ? $record->supplier->bank_account_name : 'tidak tersedia') . '</li>',
                        '<li>No. Rekening: ' . ($record->supplier->bank_account_no ? $record->supplier->bank_account_no : 'tidak tersedia') . '</li>',
                    ]) . '</ul>'
                )->html(),

            TextColumn::make('date')
                ->sortable(),

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
