<?php

namespace App\Filament\Resources\Panel\TransferCardStorageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\TransferCardStorageResource;

class EditTransferCardStorage extends EditRecord
{
    protected static string $resource = TransferCardStorageResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
