<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;

class PaymentStatusSelectInput extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->placeholder('Payment Status')
            ->required(fn () => Auth::user()->hasRole('admin'))
            ->hidden(fn ($operation) => $operation === 'create')
            ->disabled(fn () => Auth::user()->hasRole('staff'))
            ->native(false)
            ->preload()
            ->options([
                '1' => 'belum diperiksa',
                '2' => 'sudah dibayar',
                '3' => 'siap dibayar',
                '4' => 'periksa ulang',
            ]);
    }
}
