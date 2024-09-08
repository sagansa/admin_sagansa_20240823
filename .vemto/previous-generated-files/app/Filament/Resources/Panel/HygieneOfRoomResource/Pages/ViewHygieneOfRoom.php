<?php

namespace App\Filament\Resources\Panel\HygieneOfRoomResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\HygieneOfRoomResource;

class ViewHygieneOfRoom extends ViewRecord
{
    protected static string $resource = HygieneOfRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
