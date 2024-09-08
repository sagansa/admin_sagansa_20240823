<?php

namespace App\Filament\Resources\Panel\ContractEmployeeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\ContractEmployeeResource;

class EditContractEmployee extends EditRecord
{
    protected static string $resource = ContractEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
