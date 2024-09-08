<?php

namespace App\Filament\Resources\Panel\PresenceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\PresenceResource;

class EditPresence extends EditRecord
{
    protected static string $resource = PresenceResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
