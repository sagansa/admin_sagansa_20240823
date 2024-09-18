<?php

namespace App\Filament\Resources\Panel\SalesOrderOnlinesResource\Pages;

use App\Filament\Resources\Panel\SalesOrderOnlinesResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListSalesOrderOnlines extends ListRecords
{
    protected static string $resource = SalesOrderOnlinesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return SalesOrderOnlinesResource::getWidgets();
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'belum dikirim' => Tab::make()->query(fn ($query) => $query->where('delivery_status', '1')),
            'valid' => Tab::make()->query(fn ($query) => $query->where('delivery_status', '2')),
            'sudah dikirim' => Tab::make()->query(fn ($query) => $query->where('delivery_status', '3')),
            'siap dikirim' => Tab::make()->query(fn ($query) => $query->where('delivery_status', '4')),
            'perbaiki' => Tab::make()->query(fn ($query) => $query->where('delivery_status', '5')),
            'dikembalikan' => Tab::make()->query(fn ($query) => $query->where('delivery_status', '6')),
        ];
    }
}
