<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\TextColumn;

class CurrencyColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->alignRight()
            ->numeric(thousandsSeparator: '.')
            ->prefix('Rp ');
    }
}
