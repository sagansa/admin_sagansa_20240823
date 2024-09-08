<?php

namespace App\Filament\Resources\Panel\TransferStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\TransferStockResource;

class ViewTransferStock extends ViewRecord
{
    protected static string $resource = TransferStockResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
