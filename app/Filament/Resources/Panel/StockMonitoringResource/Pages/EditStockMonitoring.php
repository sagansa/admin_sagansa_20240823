<?php

namespace App\Filament\Resources\Panel\StockMonitoringResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\StockMonitoringResource;

class EditStockMonitoring extends EditRecord
{
    protected static string $resource = StockMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
