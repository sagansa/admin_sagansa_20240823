<?php

namespace App\Filament\Resources\Panel\StorageStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\StorageStockResource;

class ViewStorageStock extends ViewRecord
{
    protected static string $resource = StorageStockResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
