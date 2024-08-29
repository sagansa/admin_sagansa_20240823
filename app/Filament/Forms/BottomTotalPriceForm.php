<?php

namespace App\Filament\Forms;

use App\Models\SalesOrderOnline;
use Filament\Forms\Components\{RichEditor, TextInput};
use Illuminate\Support\Facades\Auth;

class BottomTotalPriceForm
{
    public static function schema(): array
    {
        return [

            TextInput::make('shipping_cost')
                ->label('Shipping Cost')
                ->numeric()
                ->default(0)
                ->minValue(0)
                ->prefix('Rp ')
                ->visible(fn () => request()->routeIs('sales-order-direct*'))
                ->reactive(),

            TextInput::make('total_price')
                ->label('Total Price')
                ->numeric()
                ->minValue(0)
                ->prefix('Rp ')
                ->readOnly()
                ->reactive(),

            RichEditor::make('notes')
                ->nullable()
                ->string()
                ->fileAttachmentsVisibility('public'),

        ];
    }
}
