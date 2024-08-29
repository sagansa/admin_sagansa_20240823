<?php

namespace App\Filament\Resources\Panel\DailySalaryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\DailySalaryResource;

class ListDailySalaries extends ListRecords
{
    protected static string $resource = DailySalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
