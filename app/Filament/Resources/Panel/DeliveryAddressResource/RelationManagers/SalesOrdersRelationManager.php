<?php

namespace App\Filament\Resources\Panel\DeliveryAddressResource\RelationManagers;

use App\Filament\Forms\StoreSelect;
use Filament\Forms;
use Filament\Tables;
use Filament\Schemas\Schema;
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
                // \Filament\Actions\CreateAction::make(),

                // \Filament\Actions\AttachAction::make()->form(
                //     fn(\Filament\Actions\AttachAction $action): array => [
                //         $action->getRecordSelect(),
                //     ]
                // ),
            ])
            ->actions([
                // \Filament\Actions\EditAction::make(),
                // \Filament\Actions\DeleteAction::make(),
                // \Filament\Actions\DetachAction::make()
                // ->action(function ($record) {
                //         $record->pivot->delete();
                //         $record->update(['payment_status' => 1]);
                //     }),
            ])
            ->bulkActions([
                // \Filament\Actions\BulkActionGroup::make([
                //     \Filament\Actions\DeleteBulkAction::make(),

                //     \Filament\Actions\DetachBulkAction::make(),
                // ]),
            ]);
    }
}
