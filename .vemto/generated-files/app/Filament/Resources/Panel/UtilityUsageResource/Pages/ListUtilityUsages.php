<?php

namespace App\Filament\Resources\Panel\UtilityUsageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\UtilityUsageResource;

class ListUtilityUsages extends ListRecords
{
    protected static string $resource = UtilityUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
