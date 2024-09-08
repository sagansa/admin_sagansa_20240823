<?php

namespace App\Filament\Resources\Panel\UtilityUsageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\UtilityUsageResource;

class ViewUtilityUsage extends ViewRecord
{
    protected static string $resource = UtilityUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
