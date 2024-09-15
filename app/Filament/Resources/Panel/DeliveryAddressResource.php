<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\Sales;
use App\Filament\Forms\DeliveryAddressForm;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DeliveryAddress;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Panel\DeliveryAddressResource\Pages;
use App\Filament\Resources\Panel\DeliveryAddressResource\RelationManagers;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Filters\SelectFilter;
use Humaidem\FilamentMapPicker\Fields\OSMMap;
use Illuminate\Support\Facades\Auth;

class DeliveryAddressResource extends Resource
{
    protected static ?string $model = DeliveryAddress::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Sales';

    protected static ?string $pluralLabel = 'Delivery Address';

    protected static ?string $cluster = Sales::class;

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
                Grid::make(['default' => 1])
                    ->schema([
                        OSMMap::make('location')
                            ->label('Location')
                            ->showMarker()
                            ->draggable()
                            ->extraControl([
                                'zoomDelta'           => 1,
                                'zoomSnap'            => 0.25,
                                'wheelPxPerZoomLevel' => 60
                            ])
                            ->afterStateHydrated(function (Get $get, Set $set, $record) {
                                if ($record) {
                                    $latitude = $record->latitude;
                                    $longitude = $record->longitude;

                                    if ($latitude && $longitude) {
                                            $set('location', ['lat' => $latitude, 'lng' => $longitude]);
                                    }
                                }
                            })
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $set('latitude', $state['lat']);
                                $set('longitude', $state['lng']);
                            })
                            // tiles url (refer to https://www.spatialbias.com/2018/02/qgis-3.0-xyz-tile-layers/)
                            ->tilesUrl('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
                        ),

                        TextInput::make('latitude')->readOnly(),

                        TextInput::make('longitude')->readOnly(),

                        TextInput::make('name')
                            ->placeholder('Rumah, Kantor, atau Lain-lain')
                            ->required(),

                        TextInput::make('recipient_name')
                            ->required(),

                        TextInput::make('recipient_telp_no')
                            ->label('Telephone')
                            ->numeric()
                            ->nullable(),

                        TextInput::make('address')
                            ->required(),

                        Select::make('province_id')
                            ->hiddenLabel()
                            ->placeholder('Province')
                            ->required()
                            ->relationship('province', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('city_id', null);
                                $set('district_id', null);
                                $set('subdistrict_id', null);
                                $set('postal_code_id', null);
                            }),

                        Select::make('city_id')
                            ->required()
                            ->relationship('city', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->reactive()
                            ->options(function (callable $get) {
                                $provinceId = $get('province_id');
                                return \App\Models\City::where('province_id', $provinceId)->pluck('name', 'id');
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('district_id', null);
                                $set('subdistrict_id', null);
                                $set('postal_code_id', null);
                            }),

                        Select::make('district_id')
                            ->nullable()
                            ->relationship('district', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->reactive()
                            ->options(function (callable $get) {
                                $cityId = $get('city_id');
                                return \App\Models\District::where('city_id', $cityId)->pluck('name', 'id');
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('subdistrict_id', null);
                                $set('postal_code_id', null);
                            }),

                        Select::make('subdistrict_id')
                            ->nullable()
                            ->relationship('subdistrict', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->reactive()
                            ->options(function (callable $get) {
                                $districtId = $get('district_id');
                                return \App\Models\Subdistrict::where('district_id', $districtId)->pluck('name', 'id');
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('postal_code_id', null);
                            }),

                        Select::make('postal_code_id')
                            ->label('Postal Code')
                            ->nullable()
                            ->preload()
                            ->native(false)
                            ->reactive()
                            ->options(function (callable $get) {
                                $provinceId = $get('province_id');
                                $cityId = $get('city_id');
                                $districtId = $get('district_id');
                                $subdistrictId = $get('subdistrict_id');
                                return \App\Models\PostalCode::where('province_id', $provinceId)
                                    ->where('city_id', $cityId)
                                    ->where('district_id', $districtId)
                                    ->where('subdistrict_id', $subdistrictId)
                                    ->pluck('postal_code', 'id');
                            }),

                        Select::make('user_id')
                            ->label('User')
                            ->hidden(fn () => !Auth::user()->hasRole('admin'))
                            ->nullable()
                            ->searchable()
                            ->relationship('user', 'name')
                    ])
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        $query = DeliveryAddress::query();

        if (Auth::user()->hasRole('storage-staff')) {
            $query->where('for', 3);
        } elseif (Auth::user()->hasRole('sales')) {
            $query->where('user_id', Auth::id());
        } elseif (Auth::user()->hasRole('customer')) {
            $query->where('user_id', Auth::id());
        }

        return $table
            ->query($query)
            ->poll('60s')
            ->columns([
                TextColumn::make('for')
                    ->hidden(fn () => !Auth::user()->hasRole('admin'))
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        '1' => 'Direct',
                        '2' => 'Employee',
                        '3' => 'Online',
                    }),

                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('recipient_name')
                    ->searchable(),

                TextColumn::make('recipient_telp_no')
                    ->label('Telephone')
                    ->searchable(),

                TextColumn::make('city.name'),

                TextColumn::make('user.name')
                    ->visible(fn ($record) => auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))
            ])
            ->filters([
                SelectFilter::make('for')
                    ->hidden(fn () => !Auth::user()->hasRole('admin'))
                    ->options([
                        '1' => 'Direct',
                        '2' => 'Employee',
                        '3' => 'Online',
                    ]),
                SelectFilter::make('role')
                    ->hidden(fn () => !Auth::user()->hasRole('admin'))
                    ->options([
                        'customer' => 'Customer',
                        'storage-staff' => 'Storage Staff',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SalesOrdersRelationManager::class
        ];
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
