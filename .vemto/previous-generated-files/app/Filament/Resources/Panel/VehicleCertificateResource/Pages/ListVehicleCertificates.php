<?php

namespace App\Filament\Resources\Panel\VehicleCertificateResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\VehicleCertificateResource;

class ListVehicleCertificates extends ListRecords
{
    protected static string $resource = VehicleCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
