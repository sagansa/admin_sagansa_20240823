<?php

namespace App\Filament\Resources\Panel\MonthlySalaryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\MonthlySalaryResource;

class ViewMonthlySalary extends ViewRecord
{
    protected static string $resource = MonthlySalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
