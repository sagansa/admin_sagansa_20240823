<?php

namespace App\Filament\Resources\Panel\UserCashlessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\UserCashlessResource;

class EditUserCashless extends EditRecord
{
    protected static string $resource = UserCashlessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
