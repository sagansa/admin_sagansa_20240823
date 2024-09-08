<?php

namespace App\Filament\Resources\Panel\VehicleTaxResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\VehicleTaxResource;

class ListVehicleTaxes extends ListRecords
{
    protected static string $resource = VehicleTaxResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
