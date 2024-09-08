<?php

namespace App\Filament\Resources\Panel\MovementAssetAuditResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\MovementAssetAuditResource;

class EditMovementAssetAudit extends EditRecord
{
    protected static string $resource = MovementAssetAuditResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
