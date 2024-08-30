<?php

namespace App\Filament\Resources\Panel\UtilityBillResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\UtilityBillResource;

class ViewUtilityBill extends ViewRecord
{
    protected static string $resource = UtilityBillResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
