<?php

namespace App\Filament\Resources\Panel\AccountCashlessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\AccountCashlessResource;

class EditAccountCashless extends EditRecord
{
    protected static string $resource = AccountCashlessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
