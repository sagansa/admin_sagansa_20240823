<?php

namespace App\Filament\Resources\Panel\StockMonitoringResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\StockMonitoringResource;

class ViewStockMonitoring extends ViewRecord
{
    protected static string $resource = StockMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
