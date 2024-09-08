<?php

namespace App\Filament\Resources\Panel\CashlessProviderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\CashlessProviderResource;

class ViewCashlessProvider extends ViewRecord
{
    protected static string $resource = CashlessProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
