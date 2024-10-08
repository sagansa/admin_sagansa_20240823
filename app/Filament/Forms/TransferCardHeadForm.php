<?php

namespace App\Filament\Forms;

use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\BaseSelect;
use App\Models\DetailInvoice;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Group;

class TransferCardHeadForm
{
    public static function schema(): array
    {
        return [
            Group::make([
                ImageInput::make('image'),
            ])->columnSpan(2),

            DateInput::make('date'),

            BaseSelect::make('received_by_id')
                ->relationship('receivedBy', 'name', fn(Builder $query) => $query
                    ->whereHas('roles', fn(Builder $query) => $query
                        ->where('name', 'staff') || $query
                        ->where('name', 'supervisor')))
                ->searchable(),

            BaseSelect::make('from_store_id')
                ->relationship(
                    name: 'storeFrom',
                    titleAttribute: 'nickname',
                    modifyQueryUsing: fn(Builder $query) => $query->where('status', '<>', 8)->orderBy('name', 'asc'),
                )
                ->searchable(),

            BaseSelect::make('to_store_id')
                ->relationship(
                    name: 'storeTo',
                    titleAttribute: 'nickname',
                    modifyQueryUsing: fn(Builder $query) => $query->where('status', '<>', 8)->orderBy('name', 'asc'),
                )
                ->searchable(),


        ];
    }
}
