<?php

namespace App\Filament\Resources\Panel\ContractEmployeeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\ContractEmployeeResource;

class ListContractEmployees extends ListRecords
{
    protected static string $resource = ContractEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
