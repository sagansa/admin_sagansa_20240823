<?php

namespace App\Filament\Resources\Panel\UtilityResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\UtilityResource;

class EditUtility extends EditRecord
{
    protected static string $resource = UtilityResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
