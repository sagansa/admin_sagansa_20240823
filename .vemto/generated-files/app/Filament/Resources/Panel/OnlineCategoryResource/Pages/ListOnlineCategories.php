<?php

namespace App\Filament\Resources\Panel\OnlineCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Panel\OnlineCategoryResource;

class ListOnlineCategories extends ListRecords
{
    protected static string $resource = OnlineCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
