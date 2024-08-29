<?php

namespace App\Filament\Resources\Panel\ReadinessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\ReadinessResource;

class ViewReadiness extends ViewRecord
{
    protected static string $resource = ReadinessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
