<?php

namespace App\Filament\Resources\Panel\ShiftStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\ShiftStoreResource;

class ViewShiftStore extends ViewRecord
{
    protected static string $resource = ShiftStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
