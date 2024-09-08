<?php

namespace App\Filament\Resources\Panel\OnlineShopProviderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\OnlineShopProviderResource;

class ListOnlineShopProviders extends ListRecords
{
    protected static string $resource = OnlineShopProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
