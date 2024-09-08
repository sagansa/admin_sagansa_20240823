<?php

namespace App\Filament\Resources\Panel\DeliveryServiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\DeliveryServiceResource;

class ListDeliveryServices extends ListRecords
{
    protected static string $resource = DeliveryServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
