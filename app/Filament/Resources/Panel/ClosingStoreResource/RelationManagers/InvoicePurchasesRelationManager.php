<?php

namespace App\Filament\Resources\Panel\ClosingStoreResource\RelationManagers;

use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Schemas\Components\Grid;
use App\Filament\Tables\InvoicePurchaseTable;
use Filament\Resources\RelationManagers\RelationManager;

class InvoicePurchasesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoicePurchases';

    protected static ?string $recordTitleAttribute = 'image';

    public function form(Schema $form): Schema
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                //
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns(
                InvoicePurchaseTable::schema()
            )
            ->filters([])
            ->headerActions([
                // \Filament\Actions\CreateAction::make(),

                \Filament\Actions\AttachAction::make()->form(
                    fn(\Filament\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                    ]
                ),
            ])
            ->actions([
                // \Filament\Actions\EditAction::make(),
                // \Filament\Actions\DeleteAction::make(),
                \Filament\Actions\DetachAction::make()
                    ->action(function ($record) {
                        $record->pivot->delete();
                        $record->update(['payment_status' => 1]);
                    }),
            ])
            ->bulkActions([
                // \Filament\Actions\BulkActionGroup::make([
                //     \Filament\Actions\DeleteBulkAction::make(),

                //     \Filament\Actions\DetachBulkAction::make(),
                // ]),
            ]);
    }
}
