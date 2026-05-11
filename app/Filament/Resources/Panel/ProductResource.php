<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Transaction\Settings;
use App\Filament\Columns\ActiveColumn;
use App\Filament\Forms\ActiveStatusSelect;
use App\Filament\Forms\ImageInput;
use App\Filament\Forms\Notes;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Panel\ProductResource\Pages;
use App\Filament\Resources\Panel\ProductResource\RelationManagers;
use App\Models\MaterialGroup;
use App\Models\OnlineCategory;
use Filament\Forms\Set;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Str;
use Filament\Tables\Columns\TextInputColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationGroup = 'Product';

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
            Grid::make(3)
                ->schema([
                    // Main content column
                    Grid::make(1)
                        ->schema([
                            Section::make('Informasi Dasar')
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->string()
                                        ->autofocus()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('slug')
                                                ->required()
                                                ->string()
                                                ->unique(Product::class, 'slug', ignoreRecord: true),

                                            Select::make('unit_id')
                                                ->required()
                                                ->relationship('unit', 'unit')
                                                ->preload(),
                                        ]),

                                    Notes::make('description'),
                                ])
                                ->collapsible(),

                            Section::make('Harga & Grosir')
                                ->description('Kelola harga online dan tier harga grosir')
                                ->schema([
                                    TextInput::make('online_price')
                                        ->label('Harga Online Standar')
                                        ->numeric()
                                        ->step(1)
                                        ->prefix('Rp')
                                        ->helperText('Harga ini akan digunakan jika jumlah pembelian tidak masuk ke dalam tier grosir apa pun.')
                                        ->visible(fn () => auth()->user()->hasRole(['super_admin', 'admin'])),

                                    Repeater::make('priceTiers')
                                        ->label('Tier Harga Grosir')
                                        ->relationship('priceTiers')
                                        ->schema([
                                            Grid::make(4)
                                                ->schema([
                                                    TextInput::make('min_quantity')
                                                        ->label('Min Qty')
                                                        ->numeric()
                                                        ->required()
                                                        ->minValue(1)
                                                        ->default(1),
                                                    
                                                    TextInput::make('max_quantity')
                                                        ->label('Max Qty')
                                                        ->numeric()
                                                        ->minValue(1)
                                                        ->nullable()
                                                        ->placeholder('Tak terhingga')
                                                        ->helperText('Kosongkan untuk ∞'),

                                                    TextInput::make('price')
                                                        ->label('Harga Tier')
                                                        ->numeric()
                                                        ->required()
                                                        ->prefix('Rp'),

                                                    TextInput::make('label')
                                                        ->label('Label (Opsional)')
                                                        ->string()
                                                        ->nullable()
                                                        ->placeholder('Contoh: Grosir A'),
                                                ]),
                                        ])
                                        ->defaultItems(0)
                                        ->addActionLabel('Tambah Tier Harga')
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                                ])
                                ->collapsible(),
                        ])
                        ->columnSpan(2),

                    // Sidebar column
                    Grid::make(1)
                        ->schema([
                            Section::make('Media Produk')
                                ->schema([
                                    ImageInput::make('image')
                                        ->label('Foto Utama')
                                        ->directory('images/Product')
                                        ->image(),
                                ])
                                ->collapsible(),

                            Section::make('Status & Visibilitas')
                                ->schema([
                                    ActiveStatusSelect::make('request')
                                        ->label('Status Request')
                                        ->default('2'),

                                    ActiveStatusSelect::make('remaining')
                                        ->label('Status Stok')
                                        ->default('2'),
                                ])
                                ->collapsible(),

                            Section::make('Pengelompokan')
                                ->schema([
                                    Select::make('online_category_id')
                                        ->required()
                                        ->relationship('onlineCategory', 'name')
                                        ->preload(),

                                    Select::make('material_group_id')
                                        ->required()
                                        ->relationship('materialGroup', 'name')
                                        ->preload(),

                                    Select::make('payment_type_id')
                                        ->required()
                                        ->relationship('paymentType', 'name')
                                        ->preload(),
                                ])
                                ->collapsible(),

                            Section::make('Identifikasi')
                                ->schema([
                                    TextInput::make('sku')
                                        ->label('SKU')
                                        ->nullable()
                                        ->string(),

                                    TextInput::make('barcode')
                                        ->label('Barcode')
                                        ->nullable()
                                        ->string(),
                                ])
                                ->collapsible()
                                ->collapsed(),
                        ])
                        ->columnSpan(1),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                ImageColumn::make('image')->visibility('public'),

                TextColumn::make('name')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('unit.unit'),

                ActiveColumn::make('request'),

                ActiveColumn::make('remaining'),

                TextColumn::make('paymentType.name'),

                SelectColumn::make('material_group_id')
                    ->label('Material Group')
                    ->options(MaterialGroup::query()->pluck('name', 'id')),

                SelectColumn::make('online_category_id')
                    ->label('Online Category')
                    ->options(OnlineCategory::query()->pluck('name', 'id')),

                TextInputColumn::make('online_price')
                    ->label('Online Price')
                    ->type('number')
                    ->alignEnd()
                    ->sortable()
                    ->visible(fn () => auth()->user()->hasRole(['super_admin', 'admin'])),

                TextColumn::make('user.name'),
            ])
            ->filters([

                SelectFilter::make('material_group_id')
                    ->multiple()
                    ->preload()
                    ->label('Material Group')
                    ->relationship('materialGroup', 'name'),

                SelectFilter::make('online_category_id')
                    ->multiple()
                    ->preload()
                    ->label('Online Category')
                    ->relationship('onlineCategory', 'name'),

                SelectFilter::make('remaining')
                    ->options([
                        '1' => 'active',
                        '2' => 'inactive',
                    ]),

                SelectFilter::make('request')
                    ->options([
                        '1' => 'active',
                        '2' => 'inactive',
                    ]),

                SelectFilter::make('payment_type_id')
                    ->label('Payment Type')
                    ->options([
                        '1' => 'transfer',
                        '2' => 'tunai',
                        '3' => 'non',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    // Tables\Actions\ViewAction::make(),
                ])
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

    // public static function getRelations(): array
    // {
    //     return [
    //         RelationManagers\ImagesRelationManager::class,
    //     ];
    // }

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
