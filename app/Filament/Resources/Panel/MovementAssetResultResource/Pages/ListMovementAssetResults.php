<?php

namespace App\Filament\Resources\Panel\MovementAssetResultResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\MovementAssetResultResource;

class ListMovementAssetResults extends ListRecords
{
    protected static string $resource = MovementAssetResultResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
