<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\HRD;
use App\Filament\Forms\BaseSelect;
use App\Filament\Forms\BaseTextInput;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Employee;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Panel\EmployeeResource\Pages;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ToggleColumn;
use Humaidem\FilamentMapPicker\Fields\OSMMap;
use Illuminate\Support\Facades\Auth;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = HRD::class;

    protected static ?string $navigationGroup = 'Personal Data';

    public static function getModelLabel(): string
    {
        return __('crud.employees.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.employees.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.employees.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Tabs')->tabs([
                Tab::make('Personal Information')
                    ->schema([
                        BaseTextInput::make('identity_no')
                            ->numeric()
                            ->autofocus(),

                        BaseTextInput::make('nickname'),

                        BaseTextInput::make('birth_place'),

                        BaseTextInput::make('no_telp')
                            ->numeric(),

                        DateInput::make('birth_date'),

                        BaseSelect::make('gender')
                            ->options([
                                '1' => 'male',
                                '2' => 'female',
                            ]),

                        BaseSelect::make('religion')
                            ->options([
                                '1' => 'islam',
                                '2' => 'kristen',
                                '3' => 'katholik',
                                '4' => 'hindu',
                                '5' => 'budha',
                                '6' => 'kong hu chu',
                            ]),

                        BaseSelect::make('marital_status')
                            ->options([
                                '1' => 'belum menikah',
                                '2' => 'menikah',
                                '3' => 'duda/janda'
                            ]),
                    ]),

                Tab::make('Contact Information')
                    ->schema([
                        BaseTextInput::make('fathers_name')
                            ->label('Fathers Name'),

                        BaseTextInput::make('mothers_name')
                            ->label('Mothers Name'),

                        BaseTextInput::make('parents_no_telp')
                            ->numeric(),

                        BaseTextInput::make('siblings_name')
                            ->label('Siblings Name'),

                        BaseTextInput::make('siblings_no_telp'),

                        OSMMap::make('location')
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

                        TextInput::make('latitude')->readOnly()
                            // ->hiddenLabel()
                            ->inlineLabel(),
                            // ->placeholder('Latitude'),

                        TextInput::make('longitude')->readOnly()
                            // ->hiddenLabel()
                            ->inlineLabel(),
                            // ->placeholder('Longitude'),

                        BaseTextInput::make('address'),

                        BaseSelect::make('province_id')
                            ->relationship('province', 'name')
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('city_id', null);
                                $set('district_id', null);
                                $set('subdistrict_id', null);
                                $set('postal_code_id', null);
                            }),

                        BaseSelect::make('city_id')
                            ->relationship('city', 'name')
                            ->searchable()
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
                            ->inlineLabel()
                            ->preload()
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
                            ->inlineLabel()
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
                            ->inlineLabel()
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




                    ]),

                Tab::make('Employee Infromation')
                    ->schema([
                        Toggle::make('bpjs')
                            ->rules(['boolean'])
                            ->inlineLabel()
                            ->label('BPJS')
                            ->required(),

                        TextInput::make('bank_account_no')
                            ->nullable()
                            ->inlineLabel()
                            ->numeric()
                            ->string(),

                        DatePicker::make('acceptance_date')
                            ->rules(['date'])
                            ->hidden(fn ($operation) => $operation === 'create')
                            ->disabled(fn () => Auth::user()->hasRole('staff'))
                            ->visible(fn ($record) => Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin'))
                            ->nullable(),

                        BaseSelect::make('bank_id')
                            ->relationship('bank', 'name'),


                        BaseSelect::make('driving_license')
                            ->options([
                                '1' => 'A',
                                '2' => 'C',
                                '3' => 'A dan C'
                            ]),

                        BaseSelect::make('level_of_education')
                            ->options([
                                '1' => 'tidak sekolah',
                                '2' => 'SD',
                                '3' => 'SMP',
                                '4' => 'SMA',
                                '5' => 'D1',
                                '6' => 'D3',
                                '7' => 'D4/S1'
                            ]),
                    ]),

                Tab::make('General Information')
                    ->schema([
                        ImageInput::make('image_identity_id')
                            ->label('KTP')
                            ->inlineLabel()
                            ->directory('images/Employee'),

                        ImageInput::make('image_selfie')
                            ->label('Selfie')
                            ->inlineLabel()
                            ->directory('images/Employee'),
                    ]),

                ]),
            ])->columns('full');


    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([

                ImageColumn::make('image_identity_id')->label('KTP')->visibility('public'),

                ImageColumn::make('image_selfie')->label('Selfie')->visibility('public'),

                TextColumn::make('identity_no')->label('NIK')->copyable(),

                TextColumn::make('nickname')->searchable(),

                TextColumn::make('no_telp')->searchable(),

                TextColumn::make('birth_place')->sortable(),

                TextColumn::make('birth_date')->sortable(),

                TextColumn::make('fathers_name'),

                TextColumn::make('mothers_name'),

                TextColumn::make('parents_no_telp'),

                TextColumn::make('siblings_name'),

                TextColumn::make('siblings_no_telp'),

                ToggleColumn::make('bpjs')->label('BPJS'),

                TextColumn::make('bank_account_no'),

                TextColumn::make('acceptance_date')->since(),

                TextColumn::make('bank.name'),

                TextColumn::make('employeeStatus.name'),

                TextColumn::make('gender')
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'laki-laki',
                            '2' => 'perempuan',

                        }
                    ),

                TextColumn::make('religion')
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'islam',
                            '2' => 'kristen',
                            '3' => 'katholik',
                            '4' => 'hindu',
                            '5' => 'budha',
                            '6' => 'kong hu chu',

                        }
                    ),

                TextColumn::make('driving_license')
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'A',
                            '2' => 'C',
                            '3' => 'A dan C'

                        }
                    ),

                TextColumn::make('marital_status')
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'belum menikah',
                            '2' => 'menikah',
                            '3' => 'duda/janda'

                        }
                    ),

                TextColumn::make('level_of_education')
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            '1' => 'tidak sekolah',
                            '2' => 'SD',
                            '3' => 'SMP',
                            '4' => 'SMA',
                            '5' => 'D1',
                            '6' => 'D3',
                            '7' => 'D4/S1'

                        }
                    ),
            ])
            ->filters([])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
