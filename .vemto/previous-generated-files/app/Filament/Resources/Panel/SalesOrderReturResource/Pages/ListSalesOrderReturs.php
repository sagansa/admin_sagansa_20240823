<?php

namespace App\Filament\Resources\Panel\SalesOrderReturResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\SalesOrderReturResource;

class ListSalesOrderReturs extends ListRecords
{
    protected static string $resource = SalesOrderReturResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
