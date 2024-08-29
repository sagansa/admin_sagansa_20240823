<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\TextColumn;

class StatusColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->formatStateUsing(
            fn(string $state): string => match ($state) {
                '1' => 'belum diperiksa',
                '2' => 'valid',
                '3' => 'diperbaiki',
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
