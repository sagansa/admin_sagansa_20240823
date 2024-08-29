<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Humaidem\FilamentMapPicker\Fields\OSMMap;

class AddressForm
{
    public static function schema(): array
    {
        return [

            TextInput::make('address')
                ->hiddenLabel()
                ->placeholder('Address')
                ->required(),

            Select::make('province_id')
                ->required()
                ->hiddenLabel()
                ->placeholder('Province')
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
                ->hiddenLabel()
                ->placeholder('City')
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
                ->hiddenLabel()
                ->placeholder('District')
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
                ->hiddenLabel()
                ->placeholder('Subdistrict')
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
                // ->label('Postal Code')
                ->hiddenLabel()
                ->placeholder('Postal Code')
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

            OSMMap::make('location')
                // ->label('Location')
                ->hiddenLabel()
                // ->placeholder('Postal Code')
                ->showMarker()
                ->draggable()
                ->extraControl([
                    'zoomDelta'           => 1,
                    'zoomSnap'            => 0.25,
                    'wheelPxPerZoomLevel' => 60
                ])
                ->afterStateHydrated(function (Forms\Get $get, Forms\Set $set, $record) {
                    if ($record) {
                        $latitude = $record->latitude;
                        $longitude = $record->longitude;

                        if ($latitude && $longitude) {
                                $set('location', ['lat' => $latitude, 'lng' => $longitude]);
                        }
                    }
                })
                ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
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

        ];
    }
}
