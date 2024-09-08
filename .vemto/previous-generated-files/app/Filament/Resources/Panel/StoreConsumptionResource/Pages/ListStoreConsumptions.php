<?php

namespace App\Filament\Resources\Panel\StoreConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\StoreConsumptionResource;

class ListStoreConsumptions extends ListRecords
{
    protected static string $resource = StoreConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
