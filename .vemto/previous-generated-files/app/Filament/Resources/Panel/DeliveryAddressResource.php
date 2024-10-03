<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DeliveryAddress;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\DeliveryAddressResource\Pages;
use App\Filament\Resources\Panel\DeliveryAddressResource\RelationManagers;

class DeliveryAddressResource extends Resource
{
    protected static ?string $model = DeliveryAddress::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Admin';

    public static function getModelLabel(): string
    {
        return __('crud.deliveryAddresses.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.deliveryAddresses.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.deliveryAddresses.collectionTitle');
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

                    TextInput::make('recipients_name')
                        ->required()
                        ->string(),

                    TextInput::make('recipients_telp_no')
                        ->nullable()
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

                    TextInput::make('latitude')
                        ->nullable()
                        ->numeric()
                        ->step(1),

                    TextInput::make('longitude')
                        ->nullable()
                        ->numeric()
                        ->step(1),

                    TextInput::make('for')
                        ->required()
                        ->numeric()
                        ->step(1),

                    Select::make('user_id')
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
                TextColumn::make('name'),

                TextColumn::make('recipients_name'),

                TextColumn::make('recipients_telp_no'),

                TextColumn::make('address'),

                TextColumn::make('province.name'),

                TextColumn::make('city.name'),

                TextColumn::make('district.name'),

                TextColumn::make('subdistrict.name'),

                TextColumn::make('postalCode.id'),

                TextColumn::make('latitude'),

                TextColumn::make('longitude'),

                TextColumn::make('for'),

                TextColumn::make('user_id'),
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
            'index' => Pages\ListDeliveryAddresses::route('/'),
            'create' => Pages\CreateDeliveryAddress::route('/create'),
            'view' => Pages\ViewDeliveryAddress::route('/{record}'),
            'edit' => Pages\EditDeliveryAddress::route('/{record}/edit'),
        ];
    }
}
