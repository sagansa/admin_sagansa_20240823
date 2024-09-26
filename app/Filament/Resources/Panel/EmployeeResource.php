<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Clusters\HRD;
use App\Filament\Forms\BaseTextInput;
use App\Filament\Forms\DateInput;
use App\Filament\Forms\ImageInput;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Employee;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\CheckboxColumn;
use App\Filament\Resources\Panel\EmployeeResource\Pages;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    BaseTextInput::make('identity_no')
                        ->numeric()
                        ->autofocus(),

                    BaseTextInput::make('nickname'),

                    BaseTextInput::make('no_telp')
                        ->numeric(),

                    BaseTextInput::make('birth_place'),

                    DateInput::make('birth_date'),

                    BaseTextInput::make('fathers_name')
                        ->label('Fathers Name'),

                    BaseTextInput::make('mothers_name')
                        ->label('Mothers Name'),

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
                        ->afterStateUpdated(function ($state, FGet $get, Set $set) {
                            $set('latitude', $state['lat']);
                            $set('longitude', $state['lng']);
                        })
                        // tiles url (refer to https://www.spatialbias.com/2018/02/qgis-3.0-xyz-tile-layers/)
                        ->tilesUrl('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
                    ),

                    TextInput::make('latitude')->readOnly()
                        ->hiddenLabel()
                        ->placeholder('Latitude'),

                    TextInput::make('longitude')->readOnly()
                        ->hiddenLabel()
                        ->placeholder('Longitude'),

                    BaseTextInput::make('address'),

                    Select::make('province_id')
                        ->required()
                        ->relationship('province', 'name')
                        ->searchable()
                        ->preload()

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

                    BaseTextInput::make('parents_no_telp')
                        ->numeric(),

                    BaseTextInput::make('siblings_name')
                        ->label('Siblings Name'),

                    BaseTextInput::make('siblings_no_telp'),

                    Toggle::make('bpjs')
                        ->rules(['boolean'])
                        ->label('BPJS')
                        ->required(),

                    TextInput::make('bank_account_no')
                        ->nullable()
                        ->numeric()
                        ->string(),

                    DatePicker::make('acceptance_date')
                        ->rules(['date'])
                        ->hidden(fn ($operation) => $operation === 'create')
                        ->disabled(fn () => Auth::user()->hasRole('staff'))
                        ->visible(fn ($record) => Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin'))
                        ->nullable(),

                    Select::make('bank_id')
                        ->required()
                        ->relationship('bank', 'name')
                        ->preload(),

                    Select::make('gender')
                        ->required()
                        ->options([
                            '1' => 'male',
                            '2' => 'female',
                        ])
                        ->preload(),

                    Select::make('level_of_education')
                        ->required()
                        ->options([
                            '1' => 'tidak sekolah',
                            '2' => 'SD',
                            '3' => 'SMP',
                            '4' => 'SMA',
                            '5' => 'D1',
                            '6' => 'D3',
                            '7' => 'D4/S1'
                        ])
                        ->preload(),

                    ImageInput::make('image_identity_id')
                        ->directory('images/Employee'),

                    ImageInput::make('image_selfie')
                        ->directory('images/Employee'),

                    Select::make('religion')
                        ->required()
                        ->options([
                            '1' => 'islam',
                            '2' => 'kristen',
                            '3' => 'katholik',
                            '4' => 'hindu',
                            '5' => 'budha',
                            '6' => 'kong hu chu',
                        ])
                        ->preload(),

                    Select::make('driving_license')
                        ->required()
                        ->options([
                            '1' => 'A',
                            '2' => 'C',
                            '3' => 'A dan C'
                        ])
                        ->preload(),

                    Select::make('marital_status')
                        ->required()
                        ->options([
                            '1' => 'belum menikah',
                            '2' => 'menikah',
                            '3' => 'duda/janda'
                        ])
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

                ImageColumn::make('image_identity_id')->visibility('public'),

                ImageColumn::make('image_selfie')->visibility('public'),

                TextColumn::make('identity_no'),

                TextColumn::make('nickname'),

                TextColumn::make('no_telp'),

                TextColumn::make('birth_place'),

                TextColumn::make('birth_date'),

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

                TextColumn::make('gender'),

                TextColumn::make('religion'),

                TextColumn::make('driving_license'),

                TextColumn::make('marital_status'),

                TextColumn::make('level_of_education'),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
