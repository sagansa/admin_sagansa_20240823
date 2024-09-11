<?php

namespace App\Filament\Resources\Panel\DetailRequestResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\DetailRequestResource;
use Filament\Resources\Components\Tab;

class ListDetailRequests extends ListRecords
{
    protected static string $resource = DetailRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'process' => Tab::make()->query(fn ($query) => $query->where('status', '1')),
            'done' => Tab::make()->query(fn ($query) => $query->where('status', '2')),
            'reject' => Tab::make()->query(fn ($query) => $query->where('status', '3')),
            'approved' => Tab::make()->query(fn ($query) => $query->where('status', '4')),
            'not valid' => Tab::make()->query(fn ($query) => $query->where('status', '5')),
            'not used' => Tab::make()->query(fn ($query) => $query->where('status', '6')),

        ];
    }
}
