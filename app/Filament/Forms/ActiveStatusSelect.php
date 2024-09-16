<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;

class ActiveStatusSelect extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->required()
            ->native(false)
            ->preload()
            ->options([
                '1' => 'active',
                '2' => 'inactive',
            ]);
    }
}
