<?php

namespace App\Filament\Resources\Panel\UtilityBillResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\UtilityBillResource;

class EditUtilityBill extends EditRecord
{
    protected static string $resource = UtilityBillResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
