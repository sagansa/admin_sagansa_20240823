<?php

namespace App\Filament\Resources\Panel\DetailRequestResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\DetailRequestResource;

class ViewDetailRequest extends ViewRecord
{
    protected static string $resource = DetailRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
