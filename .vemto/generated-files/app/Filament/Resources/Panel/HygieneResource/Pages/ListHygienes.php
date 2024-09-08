<?php

namespace App\Filament\Resources\Panel\HygieneResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\HygieneResource;

class ListHygienes extends ListRecords
{
    protected static string $resource = HygieneResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
