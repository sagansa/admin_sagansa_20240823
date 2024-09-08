<?php

namespace App\Filament\Resources\Panel\ContractLocationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\ContractLocationResource;

class ViewContractLocation extends ViewRecord
{
    protected static string $resource = ContractLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
