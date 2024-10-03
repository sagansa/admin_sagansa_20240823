<?php

namespace App\Filament\Tables;

use App\Filament\Columns\ImageOpenUrlColumn;
use App\Filament\Columns\StatusColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class TransferCardTable
{
    public static function schema($modelClass): array
    {
        return [
            ImageOpenUrlColumn::make('image')->visibility('public'),

            TextColumn::make('date'),

            TextColumn::make('storeFrom.nickname'),

            TextColumn::make('storeTo.nickname'),

            TextColumn::make('detailTransferCards', 'Detail Transfer Cards')
                ->label('Stocks')
                ->html()
                ->formatStateUsing(function ($record) {
                    return implode('<br>', $record->detailTransferCards->map(function ($detailTransferCard) {
                        return "{$detailTransferCard->product->name} = {$detailTransferCard->quantity} {$detailTransferCard->product->unit->unit}";
                    })->toArray());
                })
                ->extraAttributes(['class' => 'whitespace-pre-wrap']),

            StatusColumn::make('status'),

            TextColumn::make('sentBy.name'),

            TextColumn::make('receivedBy.name'),

            TextColumn::make('approvedBy.name'),
        ];
    }
}
