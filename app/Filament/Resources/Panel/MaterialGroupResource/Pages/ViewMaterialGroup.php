<?php

namespace App\Filament\Resources\Panel\MaterialGroupResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\MaterialGroupResource;

class ViewMaterialGroup extends ViewRecord
{
    protected static string $resource = MaterialGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}