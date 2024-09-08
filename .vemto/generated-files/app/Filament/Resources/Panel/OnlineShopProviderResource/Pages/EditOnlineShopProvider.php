<?php

namespace App\Filament\Resources\Panel\OnlineShopProviderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\OnlineShopProviderResource;

class EditOnlineShopProvider extends EditRecord
{
    protected static string $resource = OnlineShopProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
