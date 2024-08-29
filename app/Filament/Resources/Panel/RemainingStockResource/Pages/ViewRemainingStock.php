<?php

namespace App\Filament\Resources\Panel\RemainingStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\RemainingStockResource;

class ViewRemainingStock extends ViewRecord
{
    protected static string $resource = RemainingStockResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
