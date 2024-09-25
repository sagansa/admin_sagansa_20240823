<?php

namespace App\Filament\Filters;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class SelectEmployeeFilter extends SelectFilter
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('User')
            ->searchable()
            ->preload()
            ->hidden(fn () => !Auth::user()->hasRole('admin'))
            ->relationship('createdBy', 'name', fn (Builder $query) => $query
                ->whereHas('roles', fn (Builder $query) => $query
                    ->where('name', 'staff') || $query
                    ->where('name', 'supervisor')));
    }
}
