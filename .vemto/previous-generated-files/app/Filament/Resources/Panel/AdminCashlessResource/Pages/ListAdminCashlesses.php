<?php

namespace App\Filament\Resources\Panel\AdminCashlessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\AdminCashlessResource;

class ListAdminCashlesses extends ListRecords
{
    protected static string $resource = AdminCashlessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
