<?php

namespace App\Filament\Resources\Panel\FuelServiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\FuelServiceResource;

class ListFuelServices extends ListRecords
{
    protected static string $resource = FuelServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
