<?php

namespace App\Filament\Resources\Panel\AdminCashlessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\AdminCashlessResource;

class ViewAdminCashless extends ViewRecord
{
    protected static string $resource = AdminCashlessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
