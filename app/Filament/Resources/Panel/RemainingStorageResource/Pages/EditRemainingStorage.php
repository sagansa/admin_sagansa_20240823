<?php

namespace App\Filament\Resources\Panel\RemainingStorageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\RemainingStorageResource;

class EditRemainingStorage extends EditRecord
{
    protected static string $resource = RemainingStorageResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
