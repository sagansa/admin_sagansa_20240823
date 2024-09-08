<?php

namespace App\Filament\Resources\Panel\UserCashlessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\UserCashlessResource;

class ListUserCashlesses extends ListRecords
{
    protected static string $resource = UserCashlessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
