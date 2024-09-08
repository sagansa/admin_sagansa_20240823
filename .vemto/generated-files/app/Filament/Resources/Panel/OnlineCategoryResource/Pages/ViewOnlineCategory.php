<?php

namespace App\Filament\Resources\Panel\OnlineCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\OnlineCategoryResource;

class ViewOnlineCategory extends ViewRecord
{
    protected static string $resource = OnlineCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
