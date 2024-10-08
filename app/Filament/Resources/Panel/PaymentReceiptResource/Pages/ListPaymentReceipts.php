<?php

namespace App\Filament\Resources\Panel\PaymentReceiptResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\PaymentReceiptResource;
use Filament\Resources\Components\Tab;

class ListPaymentReceipts extends ListRecords
{
    protected static string $resource = PaymentReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        return [
            'invoice' => Tab::make()->query(fn ($query) => $query->where('payment_for', '3')),
            'fuel service' => Tab::make()->query(fn ($query) => $query->where('payment_for', '1')),
            'daily salary' => Tab::make()->query(fn ($query) => $query->where('payment_for', '2')),
        ];
    }

    protected function getDefaultTab(): ?string
    {
        return 'invoice';
    }
}
