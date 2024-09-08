<?php

namespace App\Filament\Resources\Panel\ShiftStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\ShiftStoreResource;

class EditShiftStore extends EditRecord
{
    protected static string $resource = ShiftStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
