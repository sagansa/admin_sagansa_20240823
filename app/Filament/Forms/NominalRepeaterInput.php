<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class NominalRepeaterInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->required()
            ->numeric()
            ->minValue(0)
            ->default(0)
            ->currencyMask(thousandSeparator: '.',decimalSeparator: ',',precision: 0)
            ->extraAttributes(['style' => 'text-align: right']);
    }
}
