<?php

namespace App\Filament\Resources\Panel\StockMonitoringResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\StockMonitoringResource;

class ListStockMonitorings extends ListRecords
{
    protected static string $resource = StockMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
