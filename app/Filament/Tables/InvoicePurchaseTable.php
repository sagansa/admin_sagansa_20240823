<?php

namespace App\Filament\Tables;

use App\Filament\Columns\CurrencyColumn;
use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Columns\PaymentStatusColumn;
use App\Models\InvoicePurchase;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;

class InvoicePurchaseTable
{
    public static function schema(): array
    {
        return [
            ImageOpenUrlColumn::make('image')
                ->visibility('public')
                ->url(fn($record) => asset('storage/' . $record->image)),

            TextColumn::make('date')
                ->sortable(),

            TextColumn::make('paymentType.name')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('store.nickname')
                ->searchable(),

            TextColumn::make('supplier')
                ->copyable()
                ->formatStateUsing(
                    fn($record): string => '<ul>' . implode('', [
                        '<li>Nama Supplier: ' . $record->supplier->name . '</li>',
                        '<li>Bank: ' . ($record->supplier->bank ? $record->supplier->bank->name : 'tidak tersedia') . '</li>',
                        '<li>Nama Rekening: ' . ($record->supplier->bank_account_name ? $record->supplier->bank_account_name : 'tidak tersedia') . '</li>',
                        '<li>No. Rekening: ' . ($record->supplier->bank_account_no ? $record->supplier->bank_account_no : 'tidak tersedia') . '</li>',
                    ]) . '</ul>'
                )->html(),

            TextColumn::make('detailInvoices')
                ->label('Detail Purchases')
                ->html()
                ->formatStateusing(function (InvoicePurchase $record) {
                    return implode('<br>', $record->detailInvoices->map(function ($item) {
                        $subtotalInvoice = number_format($item->subtotal_invoice, 0, ',', '.'); // add thousands separator
                        return "{$item->detailRequest->product->name} ({$item->quantity_product} {$item->detailRequest->product->unit->unit}) - Rp {$subtotalInvoice}"; // add "Rp" prefix
                    })->toArray());
                }),

            CurrencyColumn::make('total_price')
                ->summarize(Sum::make()
                    ->numeric(
                        thousandsSeparator: '.'
                    )
                    ->label('')
                    ->prefix('Rp ')),

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
