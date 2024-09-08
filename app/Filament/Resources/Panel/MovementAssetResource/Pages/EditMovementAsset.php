<?php

namespace App\Filament\Resources\Panel\MovementAssetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\MovementAssetResource;

class EditMovementAsset extends EditRecord
{
    protected static string $resource = MovementAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
