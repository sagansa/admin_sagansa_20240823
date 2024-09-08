<?php

namespace App\Filament\Resources\Panel\ProductConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\ProductConsumptionResource;

class ViewProductConsumption extends ViewRecord
{
    protected static string $resource = ProductConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
