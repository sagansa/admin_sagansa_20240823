<?php

namespace App\Filament\Resources\Panel\MovementAssetResultResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\MovementAssetResultResource;

class ViewMovementAssetResult extends ViewRecord
{
    protected static string $resource = MovementAssetResultResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
