<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use App\Models\Utility;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\UtilityResource\Pages;
use App\Filament\Resources\Panel\UtilityResource\RelationManagers;

class UtilityResource extends Resource
{
    protected static ?string $model = Utility::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Setting';

    public static function getModelLabel(): string
    {
        return __('crud.utilities.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.utilities.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.utilities.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    TextInput::make('number')
                        ->required()
                        ->string()
                        ->unique('utilities', 'number', ignoreRecord: true)
                        ->autofocus(),

                    TextInput::make('name')
                        ->required()
                        ->string(),

                    Select::make('store_id')
                        ->required()
                        ->relationship('store', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('unit_id')
                        ->required()
                        ->relationship('unit', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('utility_provider_id')
                        ->required()
                        ->relationship('utilityProvider', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('pre_post')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('category')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

                    TextInput::make('status')
                        ->required()
                        ->numeric()
                        ->step(1),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('number'),

                TextColumn::make('name'),

                TextColumn::make('store.name'),

                TextColumn::make('unit.name'),

                TextColumn::make('utilityProvider.name'),

                TextColumn::make('pre_post'),

                TextColumn::make('category'),

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
            'index' => Pages\ListUtilities::route('/'),
            'create' => Pages\CreateUtility::route('/create'),
            'view' => Pages\ViewUtility::route('/{record}'),
            'edit' => Pages\EditUtility::route('/{record}/edit'),
        ];
    }
}
