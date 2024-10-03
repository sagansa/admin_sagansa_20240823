<?php

namespace App\Filament\Resources\Panel\RemainingStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\RemainingStoreResource;

class EditRemainingStore extends EditRecord
{
    protected static string $resource = RemainingStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
