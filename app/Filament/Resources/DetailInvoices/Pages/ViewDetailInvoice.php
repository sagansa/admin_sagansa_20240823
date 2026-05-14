<?php

namespace App\Filament\Resources\DetailInvoices\Pages;

use App\Filament\Resources\DetailInvoices\DetailInvoiceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDetailInvoice extends ViewRecord
{
    protected static string $resource = DetailInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
