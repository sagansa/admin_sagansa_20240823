<?php

namespace App\Filament\Resources\Panel\EmployeeConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\EmployeeConsumptionResource;

class ListEmployeeConsumptions extends ListRecords
{
    protected static string $resource = EmployeeConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
