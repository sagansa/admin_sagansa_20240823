<?php

namespace App\Filament\Resources\DetailInvoices\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DetailInvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('invoice_purchase_id')
                    ->numeric(),
                TextEntry::make('detail_request_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('quantity_product')
                    ->numeric(),
                TextEntry::make('quantity_invoice')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('unit_invoice_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('subtotal_invoice')
                    ->numeric(),
                TextEntry::make('status')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
