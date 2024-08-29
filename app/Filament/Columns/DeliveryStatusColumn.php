<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class DeliveryStatusColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->formatStateUsing(
            fn(string $state): string => match ($state) {
                '1' => 'belum dikirim',
                '2' => 'valid',
                '3' => 'sudah dikirim',
                '4' => 'siap dikirim',
                '5' => 'perbaiki',
                '6' => 'dikembalikan',

            }
        );

        $this->badge()
             ->color(
                fn(string $state): string => match ($state) {
                    '1' => 'warning',
                    '2' => 'success',
                    '3' => 'success',
                    '4' => 'warning',
                    '5' => 'danger',
                    '6' => 'danger',

                }
             );
    }
}
