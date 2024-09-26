<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;

class BaseSelect extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->required()
            ->inlineLabel()
            ->preload();
    }
}
