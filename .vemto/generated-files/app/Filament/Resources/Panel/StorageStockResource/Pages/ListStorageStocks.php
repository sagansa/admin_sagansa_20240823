<?php

namespace App\Filament\Resources\Panel\StorageStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\StorageStockResource;

class ListStorageStocks extends ListRecords
{
    protected static string $resource = StorageStockResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
