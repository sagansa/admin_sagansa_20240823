<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\SalesOrderRetur;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\Panel\SalesOrderReturResource\Pages;
use App\Filament\Resources\Panel\SalesOrderReturResource\RelationManagers;

class SalesOrderReturResource extends Resource
{
    protected static ?string $model = SalesOrderRetur::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin';

    public static function getModelLabel(): string
    {
        return __('crud.salesOrderReturs.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.salesOrderReturs.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.salesOrderReturs.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('for')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->options([
                            '1' => 'order',
                            '2' => 'employee',
                            '3' => 'online',
                        ]),

                    DatePicker::make('delivery_date')
                        ->rules(['date'])
                        ->required()
                        ->native(false),

                    Select::make('online_shop_provider_id')
                        ->nullable()
                        ->relationship('onlineShopProvider', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('delivery_service_id')
                        ->nullable()
                        ->relationship('deliveryService', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('delivery_address_id')
                        ->nullable()
                        ->relationship('deliveryAddress', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('transfer_to_account_id')
                        ->nullable()
                        ->relationship('transferToAccount', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('shipping_cost')
                        ->nullable()
                        ->numeric()
                        ->step(1),

                    Select::make('store_id')
                        ->nullable()
                        ->relationship('store', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('receipt_no')
                        ->nullable()
                        ->string(),

                    Select::make('ordered_by_id')
                        ->nullable()
                        ->relationship('orderedBy', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('assigned_by_id')
                        ->nullable()
                        ->relationship('orderedBy', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    RichEditor::make('notes')
                        ->nullable()
                        ->string()
                        ->fileAttachmentsVisibility('public'),

                    TextInput::make('total_price')
                        ->required()
                        ->numeric()
                        ->step(1),

                    FileUpload::make('image_payment')
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                    FileUpload::make('image_delivery')
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                    Select::make('payment_status')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('delivery_status')
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
                TextColumn::make('for'),

                TextColumn::make('delivery_date')->since(),

                TextColumn::make('onlineShopProvider.name'),

                TextColumn::make('deliveryService.name'),

                TextColumn::make('deliveryAddress.name'),

                TextColumn::make('transferToAccount.name'),

                TextColumn::make('shipping_cost'),

                TextColumn::make('store.name'),

                TextColumn::make('receipt_no'),

                TextColumn::make('orderedBy.name'),

                TextColumn::make('orderedBy.name'),

                TextColumn::make('notes')->limit(255),

                TextColumn::make('total_price'),

                ImageColumn::make('image_payment')->visibility('public'),

                ImageColumn::make('image_delivery')->visibility('public'),

                TextColumn::make('payment_status'),

                TextColumn::make('delivery_status'),
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
            'index' => Pages\ListSalesOrderReturs::route('/'),
            'create' => Pages\CreateSalesOrderRetur::route('/create'),
            'view' => Pages\ViewSalesOrderRetur::route('/{record}'),
            'edit' => Pages\EditSalesOrderRetur::route('/{record}/edit'),
        ];
    }
}
