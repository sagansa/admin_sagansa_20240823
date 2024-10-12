<?php

namespace App\Filament\Resources\Panel\SalesOrderReturResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\SalesOrderReturResource;

class ViewSalesOrderRetur extends ViewRecord
{
    protected static string $resource = SalesOrderReturResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
