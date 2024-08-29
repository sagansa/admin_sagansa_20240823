<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

class CurrencyInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->mask(RawJs::make('$money($input)'))
            ->prefix('Rp')
            ->required()
            ->numeric()
            ->minValue(0)
            ->afterStateUpdated(function ($state, callable $set) {
                $set('km_now', preg_replace('/[^\d\.]/', '', $state));
            });
    }
}
