<?php

namespace App\Filament\Resources\Panel\EmployeeStatusResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\EmployeeStatusResource;

class EditEmployeeStatus extends EditRecord
{
    protected static string $resource = EmployeeStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
