<?php

namespace App\Filament\Resources\Panel\PaymentReceiptResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\PaymentReceiptResource;

class ListPaymentReceipts extends ListRecords
{
    protected static string $resource = PaymentReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
