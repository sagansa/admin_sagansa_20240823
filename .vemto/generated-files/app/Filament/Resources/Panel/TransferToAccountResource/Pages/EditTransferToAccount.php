<?php

namespace App\Filament\Resources\Panel\TransferToAccountResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\TransferToAccountResource;

class EditTransferToAccount extends EditRecord
{
    protected static string $resource = TransferToAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
