<?php

namespace App\Filament\Resources\Panel\ShiftStoreResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\ShiftStoreResource;

class ListShiftStores extends ListRecords
{
    protected static string $resource = ShiftStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
