<?php

namespace App\Filament\Resources\Panel\DailySalaryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\DailySalaryResource;

class EditDailySalary extends EditRecord
{
    protected static string $resource = DailySalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
