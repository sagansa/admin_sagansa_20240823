<?php

namespace App\Filament\Resources\Panel\OnlineShopProviderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\OnlineShopProviderResource;

class ViewOnlineShopProvider extends ViewRecord
{
    protected static string $resource = OnlineShopProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
