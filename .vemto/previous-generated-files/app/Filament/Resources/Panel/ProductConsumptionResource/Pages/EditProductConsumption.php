<?php

namespace App\Filament\Resources\Panel\ProductConsumptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\ProductConsumptionResource;

class EditProductConsumption extends EditRecord
{
    protected static string $resource = ProductConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
