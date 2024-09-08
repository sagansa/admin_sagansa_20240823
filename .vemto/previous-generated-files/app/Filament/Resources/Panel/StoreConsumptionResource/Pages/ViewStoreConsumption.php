<?php

namespace App\Filament\Resources\Panel\StoreConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\StoreConsumptionResource;

class ViewStoreConsumption extends ViewRecord
{
    protected static string $resource = StoreConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
