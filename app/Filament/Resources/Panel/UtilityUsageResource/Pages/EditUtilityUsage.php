<?php

namespace App\Filament\Resources\Panel\UtilityUsageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\UtilityUsageResource;

class EditUtilityUsage extends EditRecord
{
    protected static string $resource = UtilityUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
