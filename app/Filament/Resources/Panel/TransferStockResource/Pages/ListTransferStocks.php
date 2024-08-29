<?php

namespace App\Filament\Resources\Panel\TransferStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\TransferStockResource;

class ListTransferStocks extends ListRecords
{
    protected static string $resource = TransferStockResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
