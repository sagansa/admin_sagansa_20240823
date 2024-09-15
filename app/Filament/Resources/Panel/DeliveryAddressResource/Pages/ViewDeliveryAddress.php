<?php

namespace App\Filament\Resources\Panel\DeliveryAddressResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\DeliveryAddressResource;
use App\Models\DeliveryAddress;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

class ViewDeliveryAddress extends ViewRecord
{
    protected static string $resource = DeliveryAddressResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Detail Delivery Address')
                    ->schema([Split::make([
                        Grid::make(2)
                            ->schema([
                                Group::make([
                                    TextEntry::make('name'),
                                    TextEntry::make('recipients_name'),
                                    TextEntry::make('recipients_telp_no'),
                                    TextEntry::make('latitude'),

                                        // ->formatStateUsing(fn ($record) => '(' . $record->latitude . ', ' . $record->longitude . ')'),
                                    TextEntry::make('longitude'),
                                ]),
                                Group::make([
                                    TextEntry::make('address'),
                                    TextEntry::make('subdistrict.name'),
                                    TextEntry::make('district.name'),
                                    TextEntry::make('city.name'),
                                    TextEntry::make('province.name'),
                                    TextEntry::make('postalCode.postal_code'),
                                ])
                            ])
                        ])
                    ])
                ]);
    }
}
