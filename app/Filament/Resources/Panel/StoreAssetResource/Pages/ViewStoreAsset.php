<?php

namespace App\Filament\Resources\Panel\StoreAssetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\StoreAssetResource;

class ViewStoreAsset extends ViewRecord
{
    protected static string $resource = StoreAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
