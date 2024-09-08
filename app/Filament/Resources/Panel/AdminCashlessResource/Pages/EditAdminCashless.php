<?php

namespace App\Filament\Resources\Panel\AdminCashlessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\AdminCashlessResource;

class EditAdminCashless extends EditRecord
{
    protected static string $resource = AdminCashlessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
