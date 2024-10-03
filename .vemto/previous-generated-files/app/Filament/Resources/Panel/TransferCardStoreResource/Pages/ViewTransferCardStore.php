<?php

namespace App\Filament\Resources\Panel\TransferCardStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\TransferCardStoreResource;

class ViewTransferCardStore extends ViewRecord
{
    protected static string $resource = TransferCardStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
