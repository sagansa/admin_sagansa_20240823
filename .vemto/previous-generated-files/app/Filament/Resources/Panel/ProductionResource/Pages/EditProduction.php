<?php

namespace App\Filament\Resources\Panel\ProductionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\ProductionResource;

class EditProduction extends EditRecord
{
    protected static string $resource = ProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
