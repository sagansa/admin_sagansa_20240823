<?php

namespace App\Filament\Resources\Panel\UtilityProviderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\UtilityProviderResource;

class EditUtilityProvider extends EditRecord
{
    protected static string $resource = UtilityProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
