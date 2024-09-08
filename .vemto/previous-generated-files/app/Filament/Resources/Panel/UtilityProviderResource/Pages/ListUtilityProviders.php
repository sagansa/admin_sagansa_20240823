<?php

namespace App\Filament\Resources\Panel\UtilityProviderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\UtilityProviderResource;

class ListUtilityProviders extends ListRecords
{
    protected static string $resource = UtilityProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
