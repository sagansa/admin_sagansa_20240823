<?php

namespace App\Filament\Resources\Panel\DeliveryAddressResource\RelationManagers;

use App\Filament\Forms\StoreSelect;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Panel\DeliveryAddressResource;

class SalesOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'salesOrders';

    protected static ?string $recordTitleAttribute = 'image';

    public function table(Table $table): Table
    {
        return $table
            ->columns([])
            ->filters([])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),

                // Tables\Actions\AttachAction::make()->form(
                //     fn(Tables\Actions\AttachAction $action): array => [
                //         $action->getRecordSelect(),
                //     ]
                // ),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                // Tables\Actions\DetachAction::make()
                // ->action(function ($record) {
                //         $record->pivot->delete();
                //         $record->update(['payment_status' => 1]);
                //     }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),

                //     Tables\Actions\DetachBulkAction::make(),
                // ]),
            ]);
    }
}
