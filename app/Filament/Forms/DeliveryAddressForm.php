<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class DeliveryAddressForm
{
    public static function schema(): array
    {
        return [

            TextInput::make('location')
                ->label('Koordinat (Lat, Long)')
                ->placeholder('-6.12345, 106.78910')
                ->helperText('Paste koordinat langsung dari Google Maps (contoh: -6.123, 106.789)')
                ->afterStateHydrated(function (Get $get, Set $set, $record) {
                    if ($record && $record->latitude && $record->longitude) {
                        $set('location', "{$record->latitude}, {$record->longitude}");
                    }
                })
                ->afterStateUpdated(function ($state, Set $set) {
                    if (blank($state)) {
                        $set('latitude', null);
                        $set('longitude', null);
                        return;
                    }

                    $coords = explode(',', $state);
                    if (count($coords) === 2) {
                        $set('latitude', trim($coords[0]));
                        $set('longitude', trim($coords[1]));
                    }
                })
                ->live(onBlur: true),

            TextInput::make('latitude')
                ->hidden(),

            TextInput::make('longitude')
                ->hidden(),

            BaseTextInput::make('name')
                ->placeholder('Rumah, Kantor, atau Lain-lain'),

            BaseTextInput::make('recipient_name'),

            TextInput::make('recipient_telp_no')
                ->label('Telephone')
                ->numeric()
                ->nullable(),

            baseTextInput::make('address'),

            Select::make('province_id')
                ->hiddenLabel()
                ->placeholder('Province')
                ->required()
                ->relationship('province', 'name')
                ->searchable()
                ->preload()
                ->reactive()
                ->afterStateUpdated(function ($state, Set $set) {
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
                ->options(function (Get $get) {
                    $provinceId = $get('province_id');
                    return \App\Models\City::where('province_id', $provinceId)->pluck('name', 'id');
                })
                ->afterStateUpdated(function ($state, Set $set) {
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
                ->options(function (Get $get) {
                    $cityId = $get('city_id');
                    return \App\Models\District::where('city_id', $cityId)->pluck('name', 'id');
                })
                ->afterStateUpdated(function ($state, Set $set) {
                    $set('subdistrict_id', null);
                    $set('postal_code_id', null);
                }),

            Select::make('subdistrict_id')
                ->nullable()
                ->relationship('subdistrict', 'name')
                ->searchable()
                ->preload()
                ->reactive()
                ->options(function (Get $get) {
                    $districtId = $get('district_id');
                    return \App\Models\Subdistrict::where('district_id', $districtId)->pluck('name', 'id');
                })
                ->afterStateUpdated(function ($state, Set $set) {
                    $set('postal_code_id', null);
                }),

            Select::make('postal_code_id')
                ->label('Postal Code')
                ->nullable()
                // ->relationship('postalCode', 'postal_code')
                ->preload()
                ->reactive()
                ->options(function (Get $get) {
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



        ];
    }
}
