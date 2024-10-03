<?php

namespace App\Filament\Resources\Panel\DeliveryAddressResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\DeliveryAddressResource;

class ListDeliveryAddresses extends ListRecords
{
    protected static string $resource = DeliveryAddressResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
