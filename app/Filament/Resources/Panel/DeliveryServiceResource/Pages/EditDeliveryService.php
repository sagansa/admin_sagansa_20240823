<?php

namespace App\Filament\Resources\Panel\DeliveryServiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\DeliveryServiceResource;

class EditDeliveryService extends EditRecord
{
    protected static string $resource = DeliveryServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
