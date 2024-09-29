<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

class CurrencyMinusInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->prefix('Rp')
            ->inlineLabel()
            ->required()
            ->numeric()
            ->default(0)
            ->currencyMask(thousandSeparator: '.',decimalSeparator: ',',precision: 0);
    }
}
