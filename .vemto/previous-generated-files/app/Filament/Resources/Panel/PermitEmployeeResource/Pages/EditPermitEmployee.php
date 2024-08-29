<?php

namespace App\Filament\Resources\Panel\PermitEmployeeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\PermitEmployeeResource;

class EditPermitEmployee extends EditRecord
{
    protected static string $resource = PermitEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
