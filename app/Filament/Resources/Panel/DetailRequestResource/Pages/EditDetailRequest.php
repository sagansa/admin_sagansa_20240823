<?php

namespace App\Filament\Resources\Panel\DetailRequestResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\DetailRequestResource;

class EditDetailRequest extends EditRecord
{
    protected static string $resource = DetailRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
