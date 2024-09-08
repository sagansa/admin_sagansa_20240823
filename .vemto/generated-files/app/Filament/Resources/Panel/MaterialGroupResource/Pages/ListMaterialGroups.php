<?php

namespace App\Filament\Resources\Panel\MaterialGroupResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\MaterialGroupResource;

class ListMaterialGroups extends ListRecords
{
    protected static string $resource = MaterialGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
