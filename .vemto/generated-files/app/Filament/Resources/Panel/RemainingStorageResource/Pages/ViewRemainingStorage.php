<?php

namespace App\Filament\Resources\Panel\RemainingStorageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\RemainingStorageResource;

class ViewRemainingStorage extends ViewRecord
{
    protected static string $resource = RemainingStorageResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
