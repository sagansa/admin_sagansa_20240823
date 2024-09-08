<?php

namespace App\Filament\Resources\Panel\VehicleTaxResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\VehicleTaxResource;

class ViewVehicleTax extends ViewRecord
{
    protected static string $resource = VehicleTaxResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
