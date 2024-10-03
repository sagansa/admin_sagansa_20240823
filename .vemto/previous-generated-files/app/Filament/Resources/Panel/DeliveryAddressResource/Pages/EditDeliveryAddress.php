<?php

namespace App\Filament\Resources\Panel\DeliveryAddressResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\DeliveryAddressResource;

class EditDeliveryAddress extends EditRecord
{
    protected static string $resource = DeliveryAddressResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
