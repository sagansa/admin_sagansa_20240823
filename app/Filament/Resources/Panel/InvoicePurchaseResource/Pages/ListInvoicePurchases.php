<?php

namespace App\Filament\Resources\Panel\InvoicePurchaseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\InvoicePurchaseResource;

class ListInvoicePurchases extends ListRecords
{
    protected static string $resource = InvoicePurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
