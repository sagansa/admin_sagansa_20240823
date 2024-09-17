<?php

namespace App\Filament\Filters;

use Filament\Tables\Filters\SelectFilter;

class SelectStoreFilter extends SelectFilter
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Store')
            ->searchable()
            ->preload()
            ->native(false)
            ->relationship('store', 'nickname');
    }
}
