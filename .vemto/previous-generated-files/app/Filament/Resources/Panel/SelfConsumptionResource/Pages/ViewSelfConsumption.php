<?php

namespace App\Filament\Resources\Panel\SelfConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\SelfConsumptionResource;

class ViewSelfConsumption extends ViewRecord
{
    protected static string $resource = SelfConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}