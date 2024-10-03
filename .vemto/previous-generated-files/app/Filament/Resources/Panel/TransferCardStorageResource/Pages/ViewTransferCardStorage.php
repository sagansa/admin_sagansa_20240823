<?php

namespace App\Filament\Resources\Panel\TransferCardStorageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\TransferCardStorageResource;

class ViewTransferCardStorage extends ViewRecord
{
    protected static string $resource = TransferCardStorageResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
