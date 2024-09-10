<?php

namespace App\Filament\Resources\Panel\DailySalaryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\DailySalaryResource;
use Filament\Resources\Components\Tab;

class ListDailySalaries extends ListRecords
{
    protected static string $resource = DailySalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'transfer' => Tab::make()->query(fn ($query) => $query->where('payment_type_id', '1')),
            'tunai' => Tab::make()->query(fn ($query) => $query->where('payment_type_id', '2')),

        ];
    }
}
