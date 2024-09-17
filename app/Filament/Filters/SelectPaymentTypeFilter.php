<?php

namespace App\Filament\Filters;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SelectPaymentTypeFilter extends SelectFilter
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Payment Type')
            ->preload()
            // ->hidden(fn () => !Auth::user()->hasRole('admin'))
            ->relationship('paymentType', 'name', fn (Builder $query) => $query->where('status','1'));
    }
}

