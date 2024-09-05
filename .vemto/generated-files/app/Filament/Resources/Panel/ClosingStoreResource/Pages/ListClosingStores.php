<?php

namespace App\Filament\Resources\Panel\ClosingStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\ClosingStoreResource;

class ListClosingStores extends ListRecords
{
    protected static string $resource = ClosingStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
