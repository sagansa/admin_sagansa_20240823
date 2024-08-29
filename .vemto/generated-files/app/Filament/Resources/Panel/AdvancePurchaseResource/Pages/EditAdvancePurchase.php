<?php

namespace App\Filament\Resources\Panel\AdvancePurchaseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Panel\AdvancePurchaseResource;

class EditAdvancePurchase extends EditRecord
{
    protected static string $resource = AdvancePurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
