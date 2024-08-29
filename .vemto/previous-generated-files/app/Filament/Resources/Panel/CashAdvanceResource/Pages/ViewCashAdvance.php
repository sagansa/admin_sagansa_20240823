<?php

namespace App\Filament\Resources\Panel\CashAdvanceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\CashAdvanceResource;

class ViewCashAdvance extends ViewRecord
{
    protected static string $resource = CashAdvanceResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
