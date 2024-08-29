<?php

namespace App\Filament\Resources\Panel\DailySalaryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\DailySalaryResource;

class ViewDailySalary extends ViewRecord
{
    protected static string $resource = DailySalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
