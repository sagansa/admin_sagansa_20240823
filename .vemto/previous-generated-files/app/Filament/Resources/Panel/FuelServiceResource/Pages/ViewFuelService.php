<?php

namespace App\Filament\Resources\Panel\FuelServiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\FuelServiceResource;

class ViewFuelService extends ViewRecord
{
    protected static string $resource = FuelServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
