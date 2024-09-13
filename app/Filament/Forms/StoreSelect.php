<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class StoreSelect extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->required()
            ->relationship(
                name: 'store',
                titleAttribute: 'nickname',
                modifyQueryUsing: fn (Builder $query) => $query->where('status', '<>', 8)->orderBy('name', 'asc'),)
            ->preload()
            // ->reactive()
            ->native(false);
    }
}
