<?php

namespace App\Filament\Resources\Panel\StoreConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\StoreConsumptionResource;

class EditStoreConsumption extends EditRecord
{
    protected static string $resource = StoreConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
