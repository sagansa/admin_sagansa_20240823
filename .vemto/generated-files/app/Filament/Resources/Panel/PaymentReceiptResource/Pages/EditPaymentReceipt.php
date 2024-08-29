<?php

namespace App\Filament\Resources\Panel\PaymentReceiptResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\PaymentReceiptResource;

class EditPaymentReceipt extends EditRecord
{
    protected static string $resource = PaymentReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
