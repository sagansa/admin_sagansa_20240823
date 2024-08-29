<?php

namespace App\Filament\Resources\Panel\AdvancePurchaseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\AdvancePurchaseResource;

class ListAdvancePurchases extends ListRecords
{
    protected static string $resource = AdvancePurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
