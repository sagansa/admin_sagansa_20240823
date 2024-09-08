<?php

namespace App\Filament\Resources\Panel\MovementAssetResultResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\MovementAssetResultResource;

class EditMovementAssetResult extends EditRecord
{
    protected static string $resource = MovementAssetResultResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
