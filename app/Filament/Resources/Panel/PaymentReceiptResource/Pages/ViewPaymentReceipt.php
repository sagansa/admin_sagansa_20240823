<?php

namespace App\Filament\Resources\Panel\PaymentReceiptResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\PaymentReceiptResource;

class ViewPaymentReceipt extends ViewRecord
{
    protected static string $resource = PaymentReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
