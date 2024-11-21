<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Stock;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\StockMonitoring;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\StockMonitoringResource\Pages;
use App\Filament\Resources\Panel\StockMonitoringResource\RelationManagers;
use App\Models\Product;
use Filament\Forms\Components\Repeater;

class StockMonitoringResource extends Resource
{
    protected static ?string $model = StockMonitoring::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Stock::class;

    protected static ?string $navigationGroup = 'Setting';

    public static function getModelLabel(): string
    {
        return __('crud.stockMonitorings.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.stockMonitorings.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.stockMonitorings.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema(static::getDetailsFormHeadSchema()),

            Section::make()->schema([static::getItemsRepeater()]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('name'),

                TextColumn::make('quantity_low'),

                TextColumn::make('category'),

                TextColumn::make('unit.name'),
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
        return [
            // RelationManagers\StockMonitoringDetailsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockMonitorings::route('/'),
            'create' => Pages\CreateStockMonitoring::route('/create'),
            'view' => Pages\ViewStockMonitoring::route('/{record}'),
            'edit' => Pages\EditStockMonitoring::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormHeadSchema(): array
    {
        return [
            Grid::make(['default' => 1])->schema([
                TextInput::make('name')
                    ->required()
                    ->string()
                    ->autofocus(),

                TextInput::make('quantity_low')
                    ->required()
                    ->numeric()
                    ->step(1),

                Select::make('category')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->options([
                        'storage' => 'Storage',
                        'store' => 'Store',
                    ]),

                Select::make('unit_id')
                    ->required()
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),
            ]),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('stockMonitoringDetails')
            ->hiddenLabel()
            ->minItems(1)
            ->relationship()
            ->schema([
                Select::make('product_id')
                    ->required()
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                TextInput::make('coefficient')
                    ->required()
                    ->numeric()
                    ->step(1),
            ]);
    }
}
