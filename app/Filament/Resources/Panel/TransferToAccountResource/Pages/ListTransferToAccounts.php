<?php

namespace App\Filament\Resources\Panel\TransferToAccountResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\TransferToAccountResource;

class ListTransferToAccounts extends ListRecords
{
    protected static string $resource = TransferToAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
