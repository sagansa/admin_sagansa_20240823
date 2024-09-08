<?php

namespace App\Filament\Resources\Panel\SelfConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\SelfConsumptionResource;

class EditSelfConsumption extends EditRecord
{
    protected static string $resource = SelfConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
