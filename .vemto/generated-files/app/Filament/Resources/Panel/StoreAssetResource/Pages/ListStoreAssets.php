<?php

namespace App\Filament\Resources\Panel\StoreAssetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\StoreAssetResource;

class ListStoreAssets extends ListRecords
{
    protected static string $resource = StoreAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
