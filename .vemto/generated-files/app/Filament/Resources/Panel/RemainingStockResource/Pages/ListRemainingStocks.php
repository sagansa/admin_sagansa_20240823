<?php

namespace App\Filament\Resources\Panel\RemainingStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\RemainingStockResource;

class ListRemainingStocks extends ListRecords
{
    protected static string $resource = RemainingStockResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
