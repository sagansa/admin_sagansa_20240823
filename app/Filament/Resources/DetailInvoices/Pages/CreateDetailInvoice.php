<?php

namespace App\Filament\Resources\DetailInvoices\Pages;

use App\Filament\Resources\DetailInvoices\DetailInvoiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDetailInvoice extends CreateRecord
{
    protected static string $resource = DetailInvoiceResource::class;
}
