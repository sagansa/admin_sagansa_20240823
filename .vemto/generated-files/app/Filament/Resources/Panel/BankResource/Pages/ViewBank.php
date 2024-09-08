<?php

namespace App\Filament\Resources\Panel\BankResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\BankResource;

class ViewBank extends ViewRecord
{
    protected static string $resource = BankResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
