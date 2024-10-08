<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class NominalInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->inlineLabel()
            ->required()
            ->numeric()
            ->minValue(0)
            ->default(0)
            ->currencyMask(thousandSeparator: '.',decimalSeparator: ',',precision: 0);
    }
}
