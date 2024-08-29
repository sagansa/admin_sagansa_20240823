<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\TextColumn;

class ActiveColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->formatStateUsing(
            fn(string $state): string => match ($state) {
                '1' => 'active',
                '2' => 'inactive',

            }
        );

        $this->badge()
             ->color(
                fn(string $state): string => match ($state) {
                    '1' => 'success',
                    '2' => 'danger',

                }
             );
    }
}
