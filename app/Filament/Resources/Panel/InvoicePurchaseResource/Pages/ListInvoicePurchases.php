<?php

namespace App\Filament\Resources\Panel\InvoicePurchaseResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\InvoicePurchaseResource;


class ListInvoicePurchases extends ListRecords
{
    protected static string $resource = InvoicePurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'belum dibayar' => Tab::make()->query(fn ($query) => $query->where('payment_status', '1')),
            'sudah dibayar' => Tab::make()->query(fn ($query) => $query->where('payment_status', '2')),
            'tidak valid' => Tab::make()->query(fn ($query) => $query->where('payment_status', '3')),

        ];
    }
}
