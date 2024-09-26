<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Products;
use App\Filament\Clusters\Transaction\Settings;
use App\Filament\Columns\ActiveColumn;
use App\Filament\Forms\ActiveStatusSelect;
use App\Filament\Forms\ImageInput;
use Filament\Tables;
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
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Panel\ProductResource\Pages;
use Filament\Forms\Set;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

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
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    ImageInput::make('image')

                        ->directory('images/Product'),

                    TextInput::make('name')
                        ->required()
                        ->string()
                        ->autofocus()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                    Select::make('unit_id')
                        ->required()
                        ->relationship('unit', 'unit')
                        ->preload(),

                    TextInput::make('slug')
                        ->required()
                        ->string()
                        ->unique(Product::class, 'slug', ignoreRecord: true),

                    TextInput::make('sku')
                        ->label('SKU')
                        ->nullable()
                        ->string(),

                    TextInput::make('barcode')
                        ->nullable()
                        ->string(),

                    ActiveStatusSelect::make('request')
                        ->default('2'),

                    ActiveStatusSelect::make('remaining')
                        ->default('2'),

                    Select::make('payment_type_id')
                        ->required()
                        ->relationship('paymentType', 'name')
                        ->preload(),

                    Select::make('material_group_id')
                        ->required()
                        ->relationship('materialGroup', 'name')
                        ->preload(),

                    Select::make('online_category_id')
                        ->required()
                        ->relationship('onlineCategory', 'name')
                        ->preload(),

                    DateTimePicker::make('deleted_at')
                        ->rules(['date'])
                        ->nullable(),

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

                TextColumn::make('name')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('unit.unit'),

                ActiveColumn::make('request'),

                ActiveColumn::make('remaining'),

                TextColumn::make('paymentType.name'),

                TextColumn::make('materialGroup.name'),

                TextColumn::make('onlineCategory.name'),

                TextColumn::make('user.name'),
            ])
            ->filters([

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
