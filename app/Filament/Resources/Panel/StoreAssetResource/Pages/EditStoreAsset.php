<?php

namespace App\Filament\Resources\Panel\StoreAssetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\StoreAssetResource;

class EditStoreAsset extends EditRecord
{
    protected static string $resource = StoreAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
