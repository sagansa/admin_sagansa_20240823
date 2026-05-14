<?php

namespace App\Filament\Resources\Panel\DetailInvoiceResource\Pages;

use App\Filament\Resources\Panel\DetailInvoiceResource;
use Filament\Resources\Pages\ListRecords;

class ListDetailInvoices extends ListRecords
{
    protected static string $resource = DetailInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
