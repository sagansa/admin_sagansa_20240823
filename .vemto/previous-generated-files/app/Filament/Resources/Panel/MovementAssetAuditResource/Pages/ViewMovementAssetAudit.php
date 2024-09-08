<?php

namespace App\Filament\Resources\Panel\MovementAssetAuditResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\MovementAssetAuditResource;

class ViewMovementAssetAudit extends ViewRecord
{
    protected static string $resource = MovementAssetAuditResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
