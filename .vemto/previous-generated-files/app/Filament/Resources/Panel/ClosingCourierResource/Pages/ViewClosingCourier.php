<?php

namespace App\Filament\Resources\Panel\ClosingCourierResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\ClosingCourierResource;

class ViewClosingCourier extends ViewRecord
{
    protected static string $resource = ClosingCourierResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
