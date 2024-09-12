<?php

namespace App\Filament\Resources\Panel\PaymentReceiptResource\RelationManagers;

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
                FileUpload::make('image')
                    ->rules(['image'])
                    ->nullable()
                    ->maxSize(1024)
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                Select::make('payment_type_id')
                    ->required()
                    ->relationship('paymentType', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                Select::make('store_id')
                    ->required()
                    ->relationship('store', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                Select::make('supplier_id')
                    ->required()
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                DatePicker::make('date')
                    ->rules(['date'])
                    ->required()
                    ->native(false),

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
                    ->preload()
                    ->native(false),

                Select::make('order_status')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->native(false),
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
                Tables\Actions\CreateAction::make(),

                Tables\Actions\AttachAction::make()->form(
                    fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                    ]
                ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
