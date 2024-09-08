<?php

namespace App\Filament\Resources\Panel\TransferStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\TransferStockResource;

class EditTransferStock extends EditRecord
{
    protected static string $resource = TransferStockResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
