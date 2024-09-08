<?php

namespace App\Filament\Resources\Panel\AccountCashlessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\AccountCashlessResource;

class ViewAccountCashless extends ViewRecord
{
    protected static string $resource = AccountCashlessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
