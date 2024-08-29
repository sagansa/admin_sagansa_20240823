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
                '1' => 'belum diperiksa',
                '2' => 'sudah dibayar',
                '3' => 'siap dibayar',
                '4' => 'periksa ulang',
                default => $state,
            }
        );

        $this->badge()
             ->color(
                fn(string $state): string => match ($state) {
                    '1' => 'warning',
                    '2' => 'success',
                    '3' => 'gray',
                    '4' => 'danger',
                    default => $state,
                }
             );
    }
}
