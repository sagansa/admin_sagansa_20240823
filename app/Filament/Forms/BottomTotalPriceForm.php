<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\{RichEditor, TextInput};

class BottomTotalPriceForm
{
    public static function schema(): array
    {
        return [

            CurrencyInput::make('shipping_cost')
                ->label('Shipping Cost')
                ->reactive(),

            CurrencyInput::make('total_price')
                ->label('Total Price')
                ->readOnly()
                ->reactive(),

            Notes::make('notes'),

        ];
    }
}
