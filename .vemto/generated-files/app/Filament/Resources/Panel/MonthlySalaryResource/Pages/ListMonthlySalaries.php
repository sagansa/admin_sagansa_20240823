<?php

namespace App\Filament\Resources\Panel\MonthlySalaryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\MonthlySalaryResource;

class ListMonthlySalaries extends ListRecords
{
    protected static string $resource = MonthlySalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
