<?php

namespace App\Filament\Resources\Panel\PermitEmployeeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\PermitEmployeeResource;

class ViewPermitEmployee extends ViewRecord
{
    protected static string $resource = PermitEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
