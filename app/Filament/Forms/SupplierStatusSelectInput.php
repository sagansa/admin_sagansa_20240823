<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;

class SupplierStatusSelectInput extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->required(fn () => Auth::user()->hasRole('admin'))
            ->hidden(fn ($operation) => $operation === 'create')
            ->disabled(fn () => Auth::user()->hasRole('staff'))
            ->native(false)
            ->preload()
            ->options([
                '1' => 'belum diperiksa',
                '2' => 'valid',
                '3' => 'blacklist',
            ]);
    }
}
