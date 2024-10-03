<?php

namespace App\Filament\Forms;

use App\Filament\Forms\DateInput;
use App\Filament\Forms\StockRepeaterForm;
use App\Filament\Forms\StoreSelect;
use Filament\Forms\Components\Section;

class StockCardForm
{
    public static function getStockCardStorage(): array
    {
        return [
            Section::make()->schema([
                DateInput::make('date')->columnSpan(1),

                StoreSelect::make('store_id')->columnSpan(1),
            ])->columns(2),

            Section::make('Detail Stock')->schema([
                StockRepeaterForm::getStorageRepeater(),
            ])
        ];
    }

    public static function getStockCardRemaining(): array
    {
        return [
            Section::make()->schema([
                DateInput::make('date')->columnSpan(1),

                StoreSelect::make('store_id')->columnSpan(1),
            ])->columns(2),

            Section::make('Detail Stock')->schema([
                StockRepeaterForm::getRemainingRepeater(),
            ])
        ];
    }
}
