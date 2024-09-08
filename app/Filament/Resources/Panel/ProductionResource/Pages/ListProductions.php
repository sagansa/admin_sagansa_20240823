<?php

namespace App\Filament\Resources\Panel\ProductionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\ProductionResource;

class ListProductions extends ListRecords
{
    protected static string $resource = ProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
