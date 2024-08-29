<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\TextColumn;

class StatusSupplierColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->formatStateUsing(
            fn(string $state): string => match ($state) {
                '1' => 'belum diperiksa',
                '2' => 'valid',
                '3' => 'blacklist',
                default => $state,
            }
        );

        $this->badge()
             ->color(
                fn(string $state): string => match ($state) {
                    '1' => 'gray',
                    '2' => 'success',
                    '3' => 'danger',
                    default => 'gray',
                }
             );
    }
}
