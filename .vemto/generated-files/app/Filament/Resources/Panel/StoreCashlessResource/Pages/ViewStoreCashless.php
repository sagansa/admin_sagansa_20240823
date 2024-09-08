<?php

namespace App\Filament\Resources\Panel\StoreCashlessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\StoreCashlessResource;

class ViewStoreCashless extends ViewRecord
{
    protected static string $resource = StoreCashlessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
