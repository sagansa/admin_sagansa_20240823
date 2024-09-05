<?php

namespace App\Filament\Resources\Panel\ClosingCourierResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\ClosingCourierResource;

class EditClosingCourier extends EditRecord
{
    protected static string $resource = ClosingCourierResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
