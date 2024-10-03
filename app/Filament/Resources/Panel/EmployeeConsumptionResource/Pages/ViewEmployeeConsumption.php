<?php

namespace App\Filament\Resources\Panel\EmployeeConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\EmployeeConsumptionResource;

class ViewEmployeeConsumption extends ViewRecord
{
    protected static string $resource = EmployeeConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
