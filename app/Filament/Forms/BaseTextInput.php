<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;

class BaseTextInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->required()
            ->inlineLabel();
    }
}
