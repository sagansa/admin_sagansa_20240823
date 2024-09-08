<?php

namespace App\Filament\Resources\Panel\BankResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\BankResource;

class EditBank extends EditRecord
{
    protected static string $resource = BankResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
