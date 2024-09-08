<?php

namespace App\Filament\Resources\Panel\VehicleCertificateResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\VehicleCertificateResource;

class ViewVehicleCertificate extends ViewRecord
{
    protected static string $resource = VehicleCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
