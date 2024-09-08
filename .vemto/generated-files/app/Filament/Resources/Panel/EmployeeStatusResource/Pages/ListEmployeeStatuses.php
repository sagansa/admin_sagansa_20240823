<?php

namespace App\Filament\Resources\Panel\EmployeeStatusResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\EmployeeStatusResource;

class ListEmployeeStatuses extends ListRecords
{
    protected static string $resource = EmployeeStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
