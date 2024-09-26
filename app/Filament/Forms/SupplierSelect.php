<?php

namespace App\Filament\Forms;

use App\Models\Supplier;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class SupplierSelect extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->required()
                ->relationship(
                    name: 'supplier',
                    modifyQueryUsing: fn (Builder $query) => $query->where('status','<>', '3')->orderBy('name', 'asc'),
                )
                ->getOptionLabelFromRecordUsing(fn (Supplier $record) => "{$record->supplier_name}")
                ->searchable()
                ->preload();
    }
}
