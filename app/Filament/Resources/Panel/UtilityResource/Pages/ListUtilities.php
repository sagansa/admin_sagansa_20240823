<?php

namespace App\Filament\Resources\Panel\UtilityResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\UtilityResource;
use Filament\Resources\Components\Tab;

class ListUtilities extends ListRecords
{
    protected static string $resource = UtilityResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'active' => Tab::make()->query(fn ($query) => $query->where('status', '1')),
            'inactive' => Tab::make()->query(fn ($query) => $query->where('status', '2')),
        ];
    }
}
