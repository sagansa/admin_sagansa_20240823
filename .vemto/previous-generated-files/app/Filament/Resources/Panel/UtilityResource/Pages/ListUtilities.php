<?php

namespace App\Filament\Resources\Panel\UtilityResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\UtilityResource;

class ListUtilities extends ListRecords
{
    protected static string $resource = UtilityResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
