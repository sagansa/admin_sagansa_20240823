<?php

namespace App\Filament\Resources\Panel\MovementAssetAuditResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\MovementAssetAuditResource;

class ListMovementAssetAudits extends ListRecords
{
    protected static string $resource = MovementAssetAuditResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
