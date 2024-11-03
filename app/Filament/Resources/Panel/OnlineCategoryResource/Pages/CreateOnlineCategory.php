<?php

namespace App\Filament\Resources\Panel\OnlineCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Panel\OnlineCategoryResource;

class CreateOnlineCategory extends CreateRecord
{
    protected static string $resource = OnlineCategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = "1";

        return $data;
    }
}
