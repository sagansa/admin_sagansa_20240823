<?php

namespace App\Filament\Resources\Panel\CashlessProviderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\CashlessProviderResource;

class ListCashlessProviders extends ListRecords
{
    protected static string $resource = CashlessProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
