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
            ->required(fn () => Auth::user()->hasRole('admin'))
            ->hidden(fn ($operation) => $operation === 'create')
            ->disabled(fn () => Auth::user()->hasRole('staff'))
            ->inlineLabel()
            ->preload()
            ->options([
                '1' => 'active',
                '2' => 'inactive',
            ]);
    }
}
