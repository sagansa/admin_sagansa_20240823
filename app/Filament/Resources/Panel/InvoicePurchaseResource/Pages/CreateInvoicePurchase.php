<?php

namespace App\Filament\Resources\Panel\InvoicePurchaseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\InvoicePurchaseResource;
use Illuminate\Support\Facades\Auth;

class CreateInvoicePurchase extends CreateRecord
{
    protected static string $resource = InvoicePurchaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['created_by_id'] = Auth::id();
        $data['payment_status'] = '1';
        $data['order_status'] = '1';

        return $data;
    }
}
