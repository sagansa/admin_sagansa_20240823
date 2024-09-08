<?php

namespace App\Filament\Resources\Panel\VehicleTaxResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\VehicleTaxResource;

class EditVehicleTax extends EditRecord
{
    protected static string $resource = VehicleTaxResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
