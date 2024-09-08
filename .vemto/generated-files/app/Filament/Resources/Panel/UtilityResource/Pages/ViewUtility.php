<?php

namespace App\Filament\Resources\Panel\UtilityResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\UtilityResource;

class ViewUtility extends ViewRecord
{
    protected static string $resource = UtilityResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
