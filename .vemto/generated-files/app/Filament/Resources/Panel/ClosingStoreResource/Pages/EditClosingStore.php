<?php

namespace App\Filament\Resources\Panel\ClosingStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\ClosingStoreResource;

class EditClosingStore extends EditRecord
{
    protected static string $resource = ClosingStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
