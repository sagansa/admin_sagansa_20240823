<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Store;
use App\Filament\Forms\StoreSelect;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\Location;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\LocationResource\Pages;
use App\Filament\Resources\Panel\LocationResource\RelationManagers;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Store::class;

    protected static ?string $navigationGroup = 'Store';

    public static function getModelLabel(): string
    {
        return __('crud.locations.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.locations.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.locations.collectionTitle');
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

                    StoreSelect::make('store_id'),

                    TextInput::make('contact_person_name')
                        ->required()
                        ->string(),

                    TextInput::make('contact_person_number')
                        ->required()
                        ->string(),

                    TextInput::make('address')
                        ->required()
                        ->string(),

                    Select::make('province_id')
                        ->required()
                        ->relationship('province', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('city_id')
                        ->required()
                        ->relationship('city', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('district_id')
                        ->required()
                        ->relationship('district', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('subdistrict_id')
                        ->required()
                        ->relationship('subdistrict', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('postal_code_id')
                        ->required()
                        ->relationship('postalCode', 'id')
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
                TextColumn::make('name'),

                TextColumn::make('store.name'),

                TextColumn::make('contact_person_name'),

                TextColumn::make('contact_person_number'),

                TextColumn::make('address'),

                TextColumn::make('province.name'),

                TextColumn::make('city.name'),

                TextColumn::make('district.name'),

                TextColumn::make('subdistrict.name'),

                TextColumn::make('postalCode.id'),
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
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'view' => Pages\ViewLocation::route('/{record}'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
