<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

class CurrencyRepeaterInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->prefix('Rp')
            ->hiddenLabel()
            ->required()
            ->numeric()
            ->minValue(0)
            ->default(0)
            ->reactive()
            ->debounce(2000)
            ->currencyMask(thousandSeparator: '.',decimalSeparator: ',',precision: 0);
    }
}
