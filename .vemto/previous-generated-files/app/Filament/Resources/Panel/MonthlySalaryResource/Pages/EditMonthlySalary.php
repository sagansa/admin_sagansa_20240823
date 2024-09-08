<?php

namespace App\Filament\Resources\Panel\MonthlySalaryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\MonthlySalaryResource;

class EditMonthlySalary extends EditRecord
{
    protected static string $resource = MonthlySalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
