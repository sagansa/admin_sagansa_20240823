<?php

namespace App\Filament\Resources\Panel\TransferCardStorageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\TransferCardStorageResource;

class ListTransferCardStorages extends ListRecords
{
    protected static string $resource = TransferCardStorageResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
