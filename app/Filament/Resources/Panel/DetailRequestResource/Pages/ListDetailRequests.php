<?php

namespace App\Filament\Resources\Panel\DetailRequestResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\DetailRequestResource;

class ListDetailRequests extends ListRecords
{
    protected static string $resource = DetailRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
