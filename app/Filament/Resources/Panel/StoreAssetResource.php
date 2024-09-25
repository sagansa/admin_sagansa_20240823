<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Asset;
use App\Filament\Clusters\Movements;
use App\Filament\Forms\Notes;
use App\Filament\Forms\StoreSelect;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\StoreAsset;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\StoreAssetResource\Pages;
use App\Filament\Resources\Panel\StoreAssetResource\RelationManagers;

class StoreAssetResource extends Resource
{
    protected static ?string $model = StoreAsset::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Asset::class;

    protected static ?string $navigationGroup = 'Movement';

    public static function getModelLabel(): string
    {
        return __('crud.storeAssets.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.storeAssets.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.storeAssets.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    StoreSelect::make('store_id'),

                    Notes::make('notes'),

                    Select::make('status')
                        ->required()
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
                TextColumn::make('store.name'),

                TextColumn::make('notes')->limit(255),

                TextColumn::make('status'),
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
            'index' => Pages\ListStoreAssets::route('/'),
            'create' => Pages\CreateStoreAsset::route('/create'),
            'view' => Pages\ViewStoreAsset::route('/{record}'),
            'edit' => Pages\EditStoreAsset::route('/{record}/edit'),
        ];
    }
}
