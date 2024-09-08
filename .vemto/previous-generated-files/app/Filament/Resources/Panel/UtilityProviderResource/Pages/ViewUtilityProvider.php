<?php

namespace App\Filament\Resources\Panel\UtilityProviderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\UtilityProviderResource;

class ViewUtilityProvider extends ViewRecord
{
    protected static string $resource = UtilityProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
