<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;

class ActiveStatusSelect extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->required()
            ->native(false)
            ->preload()
            ->options([
                '1' => 'active',
                '2' => 'inactive',
            ]);
    }
}
