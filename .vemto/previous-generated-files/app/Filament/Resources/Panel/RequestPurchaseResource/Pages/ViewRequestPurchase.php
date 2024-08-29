<?php

namespace App\Filament\Resources\Panel\RequestPurchaseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\RequestPurchaseResource;

class ViewRequestPurchase extends ViewRecord
{
    protected static string $resource = RequestPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
