<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;

class BaseSelectInput extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->required()
            ->hiddenLabel()
            ->searchable()
            ->preload()
            ->native(false);
    }
}
