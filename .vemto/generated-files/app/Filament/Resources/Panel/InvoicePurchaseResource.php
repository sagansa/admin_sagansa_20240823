<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\InvoicePurchase;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\InvoicePurchaseResource\Pages;
use App\Filament\Resources\Panel\InvoicePurchaseResource\RelationManagers;

class InvoicePurchaseResource extends Resource
{
    protected static ?string $model = InvoicePurchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin';

    public static function getModelLabel(): string
    {
        return __('crud.invoicePurchases.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.invoicePurchases.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.invoicePurchases.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
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
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('paymentType.name'),

                TextColumn::make('store.name'),

                TextColumn::make('supplier.name'),

                TextColumn::make('date')->since(),

                TextColumn::make('taxes'),

                TextColumn::make('discounts'),

                TextColumn::make('total_price'),

                TextColumn::make('notes')->limit(255),

                TextColumn::make('created_by_id'),

                TextColumn::make('createdBy.name'),

                TextColumn::make('payment_status'),

                TextColumn::make('order_status'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoicePurchases::route('/'),
            'create' => Pages\CreateInvoicePurchase::route('/create'),
            'view' => Pages\ViewInvoicePurchase::route('/{record}'),
            'edit' => Pages\EditInvoicePurchase::route('/{record}/edit'),
        ];
    }
}
