<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;

class DateInput extends DatePicker
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->rules(['date'])
            ->required()
            ->default('today')
            ->native(false);
    }
}
