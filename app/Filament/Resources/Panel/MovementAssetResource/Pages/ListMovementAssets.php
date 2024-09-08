<?php

namespace App\Filament\Resources\Panel\MovementAssetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\MovementAssetResource;

class ListMovementAssets extends ListRecords
{
    protected static string $resource = MovementAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
