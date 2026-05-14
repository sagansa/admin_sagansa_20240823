<?php

namespace App\Filament\Resources\DetailInvoices\Pages;

use App\Filament\Resources\DetailInvoices\DetailInvoiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDetailInvoices extends ListRecords
{
    protected static string $resource = DetailInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
