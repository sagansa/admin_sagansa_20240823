<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Section;

class TransferCardForm
{
    public static function getTransferCardStorage(): array
    {
        return [
            Section::make()->schema(
                TransferCardHeadForm::schema(),
            )->columns(2),

            Section::make('Detail Stock')->schema([
                TransferRepeaterForm::getStorageRepeater(),
            ])
        ];
    }

    public static function getTransferCardStore(): array
    {
        return [
            Section::make()->schema(
                TransferCardHeadForm::schema(),
            )->columns(2),

            Section::make('Detail Stock')->schema([
                TransferRepeaterForm::getStoreRepeater(),
            ])
        ];
    }
}
