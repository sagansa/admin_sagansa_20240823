<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;

class StatusSelectInput extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->inlineLabel()
            ->required(fn () => Auth::user()->hasRole('admin'))
            ->hidden(fn ($operation) => $operation === 'create')
            ->disabled(fn () => Auth::user()->hasRole('staff'))
            ->preload()
            ->options([
                '1' => 'belum diperiksa',
                '2' => 'valid',
                '3' => 'perbaiki',
                '4' => 'periksa ulang'
            ]);
    }
}
