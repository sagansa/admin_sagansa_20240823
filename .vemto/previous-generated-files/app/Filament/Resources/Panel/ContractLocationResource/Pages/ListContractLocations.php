<?php

namespace App\Filament\Resources\Panel\ContractLocationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\ContractLocationResource;

class ListContractLocations extends ListRecords
{
    protected static string $resource = ContractLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
