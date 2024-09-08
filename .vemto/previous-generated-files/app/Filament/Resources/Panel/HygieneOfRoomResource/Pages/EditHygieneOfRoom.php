<?php

namespace App\Filament\Resources\Panel\HygieneOfRoomResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\HygieneOfRoomResource;

class EditHygieneOfRoom extends EditRecord
{
    protected static string $resource = HygieneOfRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
