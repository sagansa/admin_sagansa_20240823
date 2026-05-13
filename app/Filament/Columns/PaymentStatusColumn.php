<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\TextColumn;

class PaymentStatusColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->formatStateUsing(
            fn(string $state): string => match ($state) {
                '1' => 'Belum Diperiksa',
                '2' => 'Valid / Sudah Dibayar',
                '3' => 'Tidak Valid',
                '4' => 'Menunggu Pembayaran',
                default => $state,
            }
        );

        $this->badge()
             ->color(
                fn(string $state): string => match ($state) {
                    '1' => 'warning',
                    '2' => 'success',
                    '3' => 'danger',
                    '4' => 'info',
                    default => 'gray',
                }
             );
    }
}
