<?php

namespace App\Filament\Resources\Panel\ClosingStoreResource\RelationManagers;

use App\Filament\Columns\CurrencyColumn;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\ClosingStoreResource;
use App\Filament\Tables\InvoicePurchaseTable;
use App\Models\DetailRequest;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;

class InvoicePurchasesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoicePurchases';

    protected static ?string $recordTitleAttribute = 'image';

    public function form(Form $form): Form
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
                Tables\Actions\DetachAction::make()
                ->action(function ($record) {
                        $record->pivot->delete();
                        $record->update(['payment_status' => 1]);
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),

                //     Tables\Actions\DetachBulkAction::make(),
                // ]),
            ]);
    }
}
