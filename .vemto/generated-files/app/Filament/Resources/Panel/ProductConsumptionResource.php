<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ProductConsumption;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\ProductConsumptionResource\Pages;
use App\Filament\Resources\Panel\ProductConsumptionResource\RelationManagers;

class ProductConsumptionResource extends Resource
{
    protected static ?string $model = ProductConsumption::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Stock';

    public static function getModelLabel(): string
    {
        return __('crud.productConsumptions.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.productConsumptions.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.productConsumptions.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    Select::make('product_id')
                        ->required()
                        ->relationship('product', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('status')
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
                TextColumn::make('product.name'),

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
            'index' => Pages\ListProductConsumptions::route('/'),
            'create' => Pages\CreateProductConsumption::route('/create'),
            'view' => Pages\ViewProductConsumption::route('/{record}'),
            'edit' => Pages\EditProductConsumption::route('/{record}/edit'),
        ];
    }
}
