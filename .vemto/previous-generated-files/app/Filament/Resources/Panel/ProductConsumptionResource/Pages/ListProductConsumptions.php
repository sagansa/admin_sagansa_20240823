<?php

namespace App\Filament\Resources\Panel\ProductConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\ProductConsumptionResource;

class ListProductConsumptions extends ListRecords
{
    protected static string $resource = ProductConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
