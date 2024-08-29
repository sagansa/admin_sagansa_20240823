<?php

namespace App\Filament\Resources\Panel\ReadinessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\ReadinessResource;

class ListReadinesses extends ListRecords
{
    protected static string $resource = ReadinessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
