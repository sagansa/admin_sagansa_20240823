<?php

namespace App\Filament\Resources\Panel\CashlessProviderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\CashlessProviderResource;

class EditCashlessProvider extends EditRecord
{
    protected static string $resource = CashlessProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
