<?php

namespace App\Filament\Resources\Panel\StoreCashlessResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\StoreCashlessResource;

class EditStoreCashless extends EditRecord
{
    protected static string $resource = StoreCashlessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
