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
            null => Tab::make('All'),
            'fuel service' => Tab::make()->query(fn ($query) => $query->where('status', '1')),
            'daily salary' => Tab::make()->query(fn ($query) => $query->where('status', '2')),
            'invoice' => Tab::make()->query(fn ($query) => $query->where('status', '3')),
        ];
    }
}
