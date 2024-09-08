<?php

namespace App\Filament\Resources\Panel\HygieneOfRoomResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\HygieneOfRoomResource;

class ListHygieneOfRooms extends ListRecords
{
    protected static string $resource = HygieneOfRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
