<?php

namespace App\Filament\Resources\Panel\ReadinessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\ReadinessResource;

class EditReadiness extends EditRecord
{
    protected static string $resource = ReadinessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
