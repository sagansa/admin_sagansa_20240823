<?php

namespace App\Filament\Resources\Panel\RemainingStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\RemainingStoreResource;

class ViewRemainingStore extends ViewRecord
{
    protected static string $resource = RemainingStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
