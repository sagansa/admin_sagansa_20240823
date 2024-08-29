<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\TextColumn;

class CurrencyColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->numeric(thousandsSeparator: '.')
            ->prefix('Rp ');
    }
}
