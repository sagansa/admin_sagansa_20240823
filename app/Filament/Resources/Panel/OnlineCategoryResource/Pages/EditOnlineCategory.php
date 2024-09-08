<?php

namespace App\Filament\Resources\Panel\OnlineCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\OnlineCategoryResource;

class EditOnlineCategory extends EditRecord
{
    protected static string $resource = OnlineCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
