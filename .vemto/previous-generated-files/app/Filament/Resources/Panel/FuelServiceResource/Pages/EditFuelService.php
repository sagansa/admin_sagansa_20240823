<?php

namespace App\Filament\Resources\Panel\FuelServiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\FuelServiceResource;

class EditFuelService extends EditRecord
{
    protected static string $resource = FuelServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
