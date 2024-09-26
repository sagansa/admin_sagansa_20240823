<?php

namespace App\Filament\Resources\Panel\PaymentReceiptResource\RelationManagers;

use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\StoreSelect;
use App\Filament\Forms\SupplierSelect;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Panel\PaymentReceiptResource;
use App\Filament\Tables\InvoicePurchaseTable;

class InvoicePurchasesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoicePurchases';

    protected static ?string $recordTitleAttribute = 'image';

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                ImageInput::make('image')

                    ->directory('images/InvoicePurchase'),

                Select::make('payment_type_id')
                    ->required()
                    ->relationship('paymentType', 'name')
                    ->searchable()
                    ->preload(),

                StoreSelect::make('store_id')
                    ->required(),

                SupplierSelect::make('supplier_id'),

                DateInput::make('date'),

                TextInput::make('taxes')
                    ->required()
                    ->numeric()
                    ->step(1),

                TextInput::make('discounts')
                    ->required()
                    ->numeric()
                    ->step(1),

                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->step(1),

                RichEditor::make('notes')
                    ->nullable()
                    ->string()
                    ->fileAttachmentsVisibility('public'),

                Select::make('payment_status')
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('order_status')
                    ->required()
                    ->searchable()
                    ->preload(),
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

                Tables\Actions\AttachAction::make()->form(
                    fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                    ]
                ),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\DetachAction::make()
                    ->action(function ($record) {
                            $record->pivot->delete();
                            $record->update(['status' => 1]);
                        }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
