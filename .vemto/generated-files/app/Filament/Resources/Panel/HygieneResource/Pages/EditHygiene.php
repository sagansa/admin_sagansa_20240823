<?php

namespace App\Filament\Resources\Panel\HygieneResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\HygieneResource;

class EditHygiene extends EditRecord
{
    protected static string $resource = HygieneResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
