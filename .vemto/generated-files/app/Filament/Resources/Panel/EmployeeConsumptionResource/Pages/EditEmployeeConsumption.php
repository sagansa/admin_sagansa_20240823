<?php

namespace App\Filament\Resources\Panel\EmployeeConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\EmployeeConsumptionResource;

class EditEmployeeConsumption extends EditRecord
{
    protected static string $resource = EmployeeConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
