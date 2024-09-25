<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Asset;
use App\Filament\Clusters\Movements;
use App\Filament\Forms\ImageInput;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MovementAsset;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Panel\MovementAssetResource\Pages;
use App\Filament\Resources\Panel\MovementAssetResource\RelationManagers;

class MovementAssetResource extends Resource
{
    protected static ?string $model = MovementAsset::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Asset::class;

    protected static ?string $navigationGroup = 'Movement';

    public static function getModelLabel(): string
    {
        return __('crud.movementAssets.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.movementAssets.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.movementAssets.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    ImageInput::make('image')
                        ->disk('public')
                        ->directory('images/MovementAsset'),

                    TextInput::make('qr_code')
                        ->nullable()
                        ->string(),

                    Select::make('product_id')
                        ->required()
                        ->relationship('product', 'name')
                        ->searchable()
                        ->preload(),

                    TextInput::make('good_cond_qty')
                        ->required()
                        ->numeric()
                        ->step(1),

                    TextInput::make('bad_cond_qty')
                        ->required()
                        ->numeric()
                        ->step(1),

                    Select::make('store_asset_id')
                        ->required()
                        ->relationship('storeAsset', 'notes')
                        ->searchable()
                        ->preload(),
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

                TextColumn::make('qr_code'),

                TextColumn::make('product.name'),

                TextColumn::make('good_cond_qty'),

                TextColumn::make('bad_cond_qty'),

                TextColumn::make('storeAsset.notes'),
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
            'index' => Pages\ListMovementAssets::route('/'),
            'create' => Pages\CreateMovementAsset::route('/create'),
            'view' => Pages\ViewMovementAsset::route('/{record}'),
            'edit' => Pages\EditMovementAsset::route('/{record}/edit'),
        ];
    }
}
