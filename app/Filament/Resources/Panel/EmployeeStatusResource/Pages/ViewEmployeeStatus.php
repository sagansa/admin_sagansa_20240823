<?php

namespace App\Filament\Resources\Panel\EmployeeStatusResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\EmployeeStatusResource;

class ViewEmployeeStatus extends ViewRecord
{
    protected static string $resource = EmployeeStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
