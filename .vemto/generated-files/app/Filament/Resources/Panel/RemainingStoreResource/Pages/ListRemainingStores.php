<?php

namespace App\Filament\Resources\Panel\RemainingStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\RemainingStoreResource;

class ListRemainingStores extends ListRecords
{
    protected static string $resource = RemainingStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
