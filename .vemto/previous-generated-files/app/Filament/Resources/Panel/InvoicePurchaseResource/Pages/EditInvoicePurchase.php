<?php

namespace App\Filament\Resources\Panel\InvoicePurchaseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\InvoicePurchaseResource;

class EditInvoicePurchase extends EditRecord
{
    protected static string $resource = InvoicePurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
