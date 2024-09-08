<?php

namespace App\Filament\Resources\Panel\DeliveryServiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\DeliveryServiceResource;

class ViewDeliveryService extends ViewRecord
{
    protected static string $resource = DeliveryServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
