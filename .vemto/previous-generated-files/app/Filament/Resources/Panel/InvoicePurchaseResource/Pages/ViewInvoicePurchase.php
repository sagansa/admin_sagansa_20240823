<?php

namespace App\Filament\Resources\Panel\InvoicePurchaseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\InvoicePurchaseResource;

class ViewInvoicePurchase extends ViewRecord
{
    protected static string $resource = InvoicePurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
