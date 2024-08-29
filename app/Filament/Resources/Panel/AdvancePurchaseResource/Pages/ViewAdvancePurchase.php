<?php

namespace App\Filament\Resources\Panel\AdvancePurchaseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\AdvancePurchaseResource;

class ViewAdvancePurchase extends ViewRecord
{
    protected static string $resource = AdvancePurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
