<?php

namespace App\Filament\Resources\Panel\VehicleCertificateResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\VehicleCertificateResource;

class EditVehicleCertificate extends EditRecord
{
    protected static string $resource = VehicleCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
