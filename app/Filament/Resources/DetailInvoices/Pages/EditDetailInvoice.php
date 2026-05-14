<?php

namespace App\Filament\Resources\DetailInvoices\Pages;

use App\Filament\Resources\DetailInvoices\DetailInvoiceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDetailInvoice extends EditRecord
{
    protected static string $resource = DetailInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
