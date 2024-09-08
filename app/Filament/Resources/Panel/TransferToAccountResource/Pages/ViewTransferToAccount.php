<?php

namespace App\Filament\Resources\Panel\TransferToAccountResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\TransferToAccountResource;

class ViewTransferToAccount extends ViewRecord
{
    protected static string $resource = TransferToAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
