<?php

namespace App\Filament\Resources\Panel\SalesOrderEmployeesResource\Pages;

use App\Filament\Resources\Panel\SalesOrderEmployeesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListSalesOrderEmployees extends ListRecords
{
    protected static string $resource = SalesOrderEmployeesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return SalesOrderEmployeesResource::getWidgets();
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'belum diperiksa' => Tab::make()->query(fn ($query) => $query->where('payment_status', '1')),
            'valid' => Tab::make()->query(fn ($query) => $query->where('payment_status', '2')),
            'perbaiki' => Tab::make()->query(fn ($query) => $query->where('payment_status', '3')),
            'periksa ulang' => Tab::make()->query(fn ($query) => $query->where('payment_status', '4')),
        ];
    }
}
