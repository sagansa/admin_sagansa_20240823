<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Columns\ActiveColumn;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Panel\ProductResource\Pages;
use App\Filament\Resources\Panel\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Setting';

    public static function getModelLabel(): string
    {
        return __('crud.products.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.products.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.products.collectionTitle');
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

                    TextInput::make('name')
                        ->required()
                        ->string()
                        ->autofocus(),

                    Select::make('unit_id')
                        ->required()
                        ->relationship('unit', 'unit')
                        ->preload()
                        ->native(false),

                    TextInput::make('slug')
                        ->required()
                        ->string(),

                    TextInput::make('sku')
                        ->label('SKU')
                        ->nullable()
                        ->string(),

                    TextInput::make('barcode')
                        ->nullable()
                        ->string(),



                    Select::make('request')
                        ->required()
                        ->default('2')
                        ->preload()
                        ->native(false)
                        ->options([
                            '1' => 'active',
                            '2' => 'inactive',
                        ]),

                    Select::make('remaining')
                        ->required()
                        ->default('2')
                        ->preload()
                        ->native(false)
                        ->options([
                            '1' => 'active',
                            '2' => 'inactive',
                        ]),

                    Select::make('payment_type_id')
                        ->required()
                        ->relationship('paymentType', 'name')
                        ->preload()
                        ->native(false),

                    Select::make('material_group_id')
                        ->required()
                        ->relationship('materialGroup', 'name')
                        ->preload()
                        ->native(false),

                    Select::make('online_category_id')
                        ->required()
                        ->relationship('onlineCategory', 'name')
                        ->preload()
                        ->native(false),

                    DateTimePicker::make('deleted_at')
                        ->rules(['date'])
                        ->nullable()
                        ->native(false),

                    RichEditor::make('description')
                        ->nullable()
                        ->string()
                        ->fileAttachmentsVisibility('public'),
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

                TextColumn::make('name'),

                TextColumn::make('unit.unit'),

                ActiveColumn::make('request'),

                ActiveColumn::make('remaining'),

                TextColumn::make('paymentType.name'),

                TextColumn::make('materialGroup.name'),

                TextColumn::make('onlineCategory.name'),

                TextColumn::make('user.name'),
            ])
            ->filters([Tables\Filters\TrashedFilter::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('name', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            // 'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }
}
