<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class DecimalInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->required()
            ->numeric()
            ->inlineLabel()
            ->minValue(0)
            ->default(0)
            ->currencyMask(thousandSeparator: '.',decimalSeparator: ',',precision: 2);
    }
}
