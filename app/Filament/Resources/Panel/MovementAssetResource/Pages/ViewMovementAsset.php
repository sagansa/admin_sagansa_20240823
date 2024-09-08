<?php

namespace App\Filament\Resources\Panel\MovementAssetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\MovementAssetResource;

class ViewMovementAsset extends ViewRecord
{
    protected static string $resource = MovementAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
