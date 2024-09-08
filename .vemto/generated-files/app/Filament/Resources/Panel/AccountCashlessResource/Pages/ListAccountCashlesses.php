<?php

namespace App\Filament\Resources\Panel\AccountCashlessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\AccountCashlessResource;

class ListAccountCashlesses extends ListRecords
{
    protected static string $resource = AccountCashlessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
