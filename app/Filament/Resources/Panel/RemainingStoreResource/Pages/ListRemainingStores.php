<?php

namespace App\Filament\Resources\Panel\RemainingStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\RemainingStoreResource;
use Filament\Resources\Components\Tab;

class ListRemainingStores extends ListRecords
{
    protected static string $resource = RemainingStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'belum diperiksa' => Tab::make()->query(fn ($query) => $query->where('status', '1')),
            'valid' => Tab::make()->query(fn ($query) => $query->where('status', '2')),
            'perbaiki' => Tab::make()->query(fn ($query) => $query->where('status', '3')),
            'periksa ulang' => Tab::make()->query(fn ($query) => $query->where('status', '4')),
        ];
    }
}
