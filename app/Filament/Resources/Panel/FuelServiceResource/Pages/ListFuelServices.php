<?php

namespace App\Filament\Resources\Panel\FuelServiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\FuelServiceResource;
use Filament\Resources\Components\Tab;

class ListFuelServices extends ListRecords
{
    protected static string $resource = FuelServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'belum Diperiksa' => Tab::make()->query(fn ($query) => $query->where('status', '1')),
            'valid' => Tab::make()->query(fn ($query) => $query->where('status', '2')),
            'perbaiki' => Tab::make()->query(fn ($query) => $query->where('status', '3')),
            'periksa Ulang' => Tab::make()->query(fn ($query) => $query->where('status', '4')),
        ];
    }
}
