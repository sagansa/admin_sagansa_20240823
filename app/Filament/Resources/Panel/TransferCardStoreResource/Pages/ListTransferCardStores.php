<?php

namespace App\Filament\Resources\Panel\TransferCardStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\TransferCardStoreResource;

class ListTransferCardStores extends ListRecords
{
    protected static string $resource = TransferCardStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
