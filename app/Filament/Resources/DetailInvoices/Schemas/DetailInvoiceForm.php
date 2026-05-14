<?php

namespace App\Filament\Resources\DetailInvoices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DetailInvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invoice_purchase_id')
                    ->required()
                    ->numeric(),
                TextInput::make('detail_request_id')
                    ->numeric(),
                TextInput::make('quantity_product')
                    ->required()
                    ->numeric(),
                TextInput::make('quantity_invoice')
                    ->numeric(),
                TextInput::make('unit_invoice_id')
                    ->numeric(),
                TextInput::make('subtotal_invoice')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->numeric(),
            ]);
    }
}
