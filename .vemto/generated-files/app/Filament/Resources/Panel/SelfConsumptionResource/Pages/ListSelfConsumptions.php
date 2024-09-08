<?php

namespace App\Filament\Resources\Panel\SelfConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\SelfConsumptionResource;

class ListSelfConsumptions extends ListRecords
{
    protected static string $resource = SelfConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
