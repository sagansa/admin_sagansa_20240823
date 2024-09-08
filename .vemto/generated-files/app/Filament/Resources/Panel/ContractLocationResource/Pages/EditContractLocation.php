<?php

namespace App\Filament\Resources\Panel\ContractLocationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\ContractLocationResource;

class EditContractLocation extends EditRecord
{
    protected static string $resource = ContractLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
