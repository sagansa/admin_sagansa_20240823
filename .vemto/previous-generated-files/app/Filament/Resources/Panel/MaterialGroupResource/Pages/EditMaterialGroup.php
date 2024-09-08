<?php

namespace App\Filament\Resources\Panel\MaterialGroupResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\MaterialGroupResource;

class EditMaterialGroup extends EditRecord
{
    protected static string $resource = MaterialGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
