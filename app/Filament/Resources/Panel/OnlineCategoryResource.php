<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Products;
use App\Filament\Clusters\Transaction\Settings;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\OnlineCategory;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\OnlineCategoryResource\Pages;
use App\Filament\Resources\Panel\OnlineCategoryResource\RelationManagers;

class OnlineCategoryResource extends Resource
{
    protected static ?string $model = OnlineCategory::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationGroup = 'Product';

    public static function getModelLabel(): string
    {
        return __('crud.onlineCategories.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.onlineCategories.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.onlineCategories.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    TextInput::make('name')
                        ->required()
                        ->string()
                        ->autofocus(),

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
            ->columns([TextColumn::make('name'), TextColumn::make('status')])
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
            'index' => Pages\ListOnlineCategories::route('/'),
            'create' => Pages\CreateOnlineCategory::route('/create'),
            'view' => Pages\ViewOnlineCategory::route('/{record}'),
            'edit' => Pages\EditOnlineCategory::route('/{record}/edit'),
        ];
    }
}
