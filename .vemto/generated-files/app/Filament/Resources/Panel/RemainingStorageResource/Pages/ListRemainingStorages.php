<?php

namespace App\Filament\Resources\Panel\RemainingStorageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\RemainingStorageResource;

class ListRemainingStorages extends ListRecords
{
    protected static string $resource = RemainingStorageResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
