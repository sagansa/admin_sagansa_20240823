<?php

namespace App\Filament\Resources\Panel\PresenceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\PresenceResource;

class ViewPresence extends ViewRecord
{
    protected static string $resource = PresenceResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
