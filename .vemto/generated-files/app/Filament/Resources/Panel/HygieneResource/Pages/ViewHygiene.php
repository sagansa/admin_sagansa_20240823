<?php

namespace App\Filament\Resources\Panel\HygieneResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\HygieneResource;

class ViewHygiene extends ViewRecord
{
    protected static string $resource = HygieneResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
