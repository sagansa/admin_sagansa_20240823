<?php

namespace App\Filament\Resources\Panel\ContractEmployeeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\ContractEmployeeResource;

class ViewContractEmployee extends ViewRecord
{
    protected static string $resource = ContractEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
