<?php

namespace App\Filament\Resources\Panel\DetailSalesOrderResource\Pages;

use App\Filament\Resources\Panel\DetailSalesOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListDetailSalesOrders extends ListRecords
{
    protected static string $resource = DetailSalesOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
