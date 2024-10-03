<?php

namespace App\Filament\Resources\Panel\TransferCardStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\TransferCardStoreResource;

class EditTransferCardStore extends EditRecord
{
    protected static string $resource = TransferCardStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
